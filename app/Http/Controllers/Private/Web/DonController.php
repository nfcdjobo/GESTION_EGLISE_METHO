<?php

namespace App\Http\Controllers\Private\Web;

use App\Models\Don;
use Illuminate\View\View;
use App\Models\ParametreDon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Don::with(['parametreDon']);

        // Filtres
        if ($request->filled('parametre')) {
            $query->where('parametre_fond_id', $request->parametre);
        }

        if ($request->filled('devise')) {
            $query->parDevise($request->devise);
        }

        if ($request->filled('montant_min')) {
            $query->montantMinimum($request->montant_min);
        }

        if ($request->filled('montant_max')) {
            $query->montantMaximum($request->montant_max);
        }

        if ($request->filled('periode')) {
            $periode = $request->periode;
            if ($periode === 'aujourd_hui') {
                $query->whereDate('created_at', today());
            } elseif ($periode === 'cette_semaine') {
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($periode === 'ce_mois') {
                $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
            } elseif ($periode === 'cette_annee') {
                $query->whereYear('created_at', now()->year);
            }
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom_donateur', 'like', "%{$search}%")
                    ->orWhere('prenom_donateur', 'like', "%{$search}%")
                    ->orWhere('telephone_1', 'like', "%{$search}%")
                    ->orWhereRaw("CONCAT(prenom_donateur, ' ', nom_donateur) LIKE ?", ["%{$search}%"]);
            });
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        if (in_array($sortBy, ['created_at', 'montant', 'nom_donateur', 'prenom_donateur'])) {
            $query->orderBy($sortBy, $sortDirection);
        } else {
            $query->latest();
        }

        $dons = $query->paginate(20);

        // Statistiques pour le dashboard
        $statistiques = null;
        if ($request->get('with_stats', false)) {
            $statistiques = [
                'total_dons' => Don::count(),
                'montant_total' => Don::sum('montant'),
                'dons_aujourd_hui' => Don::whereDate('created_at', today())->count(),
                'montant_aujourd_hui' => Don::whereDate('created_at', today())->sum('montant'),
                'dons_ce_mois' => Don::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
                'montant_ce_mois' => Don::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->sum('montant'),
                'par_devise' => Don::selectRaw('devise, SUM(montant) as total, COUNT(*) as nombre')
                    ->groupBy('devise')
                    ->get(),
            ];
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $dons->items(),
                'statistiques' => $statistiques,
                'pagination' => [
                    'current_page' => $dons->currentPage(),
                    'last_page' => $dons->lastPage(),
                    'per_page' => $dons->perPage(),
                    'total' => $dons->total(),
                    'has_more' => $dons->hasMorePages()
                ]
            ]);
        }

        return view('components.private.dons.index', compact('dons', 'statistiques'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $parametres = ParametreDon::actif()->publie()->get();

        if($parametres->count() <= 0) return redirect()->back()
                ->with('error', "Aucun moyen de paiement disponible")
                ->withInput();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'parametres' => $parametres,
                    'devises' => Don::DEVISES,
                ]
            ]);
        }

        return view('components.private.dons.create', compact('parametres'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parametre_fond_id' => 'required|exists:parametres_dons,id',
            'nom_donateur' => 'required|string|max:100',
            'prenom_donateur' => 'required|string|max:100',
            'telephone_1' => 'required|string|max:20',
            'telephone_2' => 'nullable|string|max:20',
            'montant' => 'required|numeric|min:0.01|max:999999999999.99',
            'devise' => 'required|string|max:10',
            'preuve' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120', // 5MB max
        ], [
            'parametre_fond_id.required' => 'Le paramètre de don est obligatoire',
            'parametre_fond_id.exists' => 'Le paramètre de don sélectionné n\'existe pas',
            'nom_donateur.required' => 'Le nom du donateur est obligatoire',
            'nom_donateur.max' => 'Le nom ne peut pas dépasser 100 caractères',
            'prenom_donateur.required' => 'Le prénom du donateur est obligatoire',
            'prenom_donateur.max' => 'Le prénom ne peut pas dépasser 100 caractères',
            'telephone_1.required' => 'Le numéro de téléphone principal est obligatoire',
            'telephone_1.max' => 'Le numéro de téléphone ne peut pas dépasser 20 caractères',
            'telephone_2.max' => 'Le numéro de téléphone secondaire ne peut pas dépasser 20 caractères',
            'montant.required' => 'Le montant est obligatoire',
            'montant.numeric' => 'Le montant doit être un nombre',
            'montant.min' => 'Le montant doit être supérieur à 0',
            'montant.max' => 'Le montant est trop élevé',
            'devise.required' => 'La devise est obligatoire',
            'devise.max' => 'La devise ne peut pas dépasser 10 caractères',
            'preuve.required' => 'La preuve de paiement est obligatoire',
            'preuve.file' => 'La preuve doit être un fichier',
            'preuve.mimes' => 'La preuve doit être une image (jpeg, png, jpg) ou un PDF',
            'preuve.max' => 'La preuve ne peut pas dépasser 5 Mo',
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
            // Upload de la preuve
            $preuvePath = $request->file('preuve')->store('preuves-dons', 'public');

            $don = Don::create([
                'parametre_fond_id' => $request->parametre_fond_id,
                'nom_donateur' => $request->nom_donateur,
                'prenom_donateur' => $request->prenom_donateur,
                'telephone_1' => $request->telephone_1,
                'telephone_2' => $request->telephone_2,
                'montant' => $request->montant,
                'devise' => $request->devise,
                'preuve' => $preuvePath,
            ]);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Don enregistré avec succès',
                    'data' => $don->load(['parametreDon'])
                ], 201);
            }

            return redirect()->route('private.dons.show', $don)
                ->with('success', 'Don enregistré avec succès');

        } catch (\Exception $e) {
            DB::rollback();

            // Supprimer le fichier uploadé en cas d'erreur
            if (isset($preuvePath) && Storage::disk('public')->exists($preuvePath)) {
                Storage::disk('public')->delete($preuvePath);
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'enregistrement: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de l\'enregistrement')
                ->withInput();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request, Don $don)
    {
        $don->load(['parametreDon.creerPar']);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $don
            ]);
        }

        return view('components.private.dons.show', compact('don'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Don $don)
    {
        $parametres = ParametreDon::actif()->get();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'don' => $don,
                    'parametres' => $parametres,
                    'devises' => Don::DEVISES,
                ]
            ]);
        }

        return view('components.private.dons.edit', compact('don', 'parametres'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Don $don)
    {
        $validator = Validator::make($request->all(), [
            'parametre_fond_id' => 'required|exists:parametres_dons,id',
            'nom_donateur' => 'required|string|max:100',
            'prenom_donateur' => 'required|string|max:100',
            'telephone_1' => 'required|string|max:20',
            'telephone_2' => 'nullable|string|max:20',
            'montant' => 'required|numeric|min:0.01|max:999999999999.99',
            'devise' => 'required|string|max:10',
            'preuve' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
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
            $dataToUpdate = [
                'parametre_fond_id' => $request->parametre_fond_id,
                'nom_donateur' => $request->nom_donateur,
                'prenom_donateur' => $request->prenom_donateur,
                'telephone_1' => $request->telephone_1,
                'telephone_2' => $request->telephone_2,
                'montant' => $request->montant,
                'devise' => $request->devise,
            ];

            // Upload nouvelle preuve si fournie
            if ($request->hasFile('preuve')) {
                // Supprimer l'ancienne preuve
                if ($don->preuve && Storage::disk('public')->exists($don->preuve)) {
                    Storage::disk('public')->delete($don->preuve);
                }

                $dataToUpdate['preuve'] = $request->file('preuve')->store('preuves-dons', 'public');
            }

            $don->update($dataToUpdate);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Don mis à jour avec succès',
                    'data' => $don->fresh()->load(['parametreDon'])
                ]);
            }

            return redirect()->route('private.dons.show', $don)
                ->with('success', 'Don mis à jour avec succès');

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
    public function destroy(Request $request, Don $don)
    {
        DB::beginTransaction();
        try {
            // Supprimer le fichier de preuve
            if ($don->preuve && Storage::disk('public')->exists($don->preuve)) {
                Storage::disk('public')->delete($don->preuve);
            }

            $don->delete();

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Don supprimé avec succès'
                ]);
            }

            return redirect()->route('private.dons.index')
                ->with('success', 'Don supprimé avec succès');

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
     * Télécharger la preuve de paiement.
     */
    public function telechargerPreuve(Request $request, Don $don)
    {
        if (!$don->preuve || !Storage::disk('public')->exists($don->preuve)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Preuve non trouvée'
                ], 404);
            }

            abort(404, 'Preuve non trouvée');
        }

        $filename = 'preuve_don_' . $don->nom_complet . '_' . $don->id . '.' .
            pathinfo($don->preuve, PATHINFO_EXTENSION);

        return Storage::disk('public')->download($don->preuve, $filename);
    }


    /**
     * Exporter les dons en CSV.
     */
    public function export(Request $request)
    {
        $query = Don::with(['parametreDon']);

        // Appliquer les mêmes filtres que l'index
        if ($request->filled('parametre_fond_id')) {
            $query->where('parametre_fond_id', $request->parametre_fond_id);
        }

        if ($request->filled('devise')) {
            $query->parDevise($request->devise);
        }

        if ($request->filled('montant_min')) {
            $query->montantMinimum($request->montant_min);
        }

        if ($request->filled('montant_max')) {
            $query->montantMaximum($request->montant_max);
        }

        if ($request->filled('periode')) {
            $periode = $request->periode;
            if ($periode === 'aujourd_hui') {
                $query->whereDate('created_at', today());
            } elseif ($periode === 'cette_semaine') {
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($periode === 'ce_mois') {
                $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
            } elseif ($periode === 'cette_annee') {
                $query->whereYear('created_at', now()->year);
            }
        }

        $dons = $query->orderBy('created_at', 'desc')->get();

        $filename = 'dons_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($dons) {
            $file = fopen('php://output', 'w');

            // En-têtes CSV
            fputcsv($file, [
                'ID',
                'Date',
                'Nom complet',
                'Téléphone 1',
                'Téléphone 2',
                'Montant',
                'Devise',
                'Opérateur',
                'Type de paiement',
                'Numéro de compte',
            ], ';');

            // Données
            foreach ($dons as $don) {
                fputcsv($file, [
                    $don->id,
                    $don->created_at->format('d/m/Y H:i'),
                    $don->nom_complet,
                    $don->telephone_1,
                    $don->telephone_2 ?? '',
                    $don->montant,
                    $don->devise,
                    $don->parametreDon->operateur ?? '',
                    $don->parametreDon->type_libelle ?? '',
                    $don->parametreDon->numero_compte ?? '',
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }



    /**
     * Statistiques détaillées des dons.
     */
    public function statistiques(Request $request)
    {
        // Période par défaut : ce mois
        $periode = $request->get('periode', 'ce_mois');

        // Base query pour appliquer les filtres
        $baseQuery = Don::query();

        // Appliquer la période
        switch ($periode) {
            case 'aujourd_hui':
                $baseQuery->whereDate('dons.created_at', today());
                break;
            case 'cette_semaine':
                $baseQuery->whereBetween('dons.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'ce_mois':
                $baseQuery->whereMonth('dons.created_at', now()->month)
                    ->whereYear('dons.created_at', now()->year);
                break;
            case 'cette_annee':
                $baseQuery->whereYear('dons.created_at', now()->year);
                break;
            case 'personnalisee':
                if ($request->filled(['date_debut', 'date_fin'])) {
                    $baseQuery->whereBetween('dons.created_at', [
                        $request->date_debut . ' 00:00:00',
                        $request->date_fin . ' 23:59:59'
                    ]);
                }
                break;
        }

        // Cloner la requête de base pour chaque statistique
        $resumeQuery = clone $baseQuery;
        $parDeviseQuery = clone $baseQuery;
        $parOperateurQuery = clone $baseQuery;
        $parTypePaiementQuery = clone $baseQuery;
        $topDonateursQuery = clone $baseQuery;

        $statistiques = [
            'resume' => [
                'total_dons' => $resumeQuery->count(),
                'montant_total' => $resumeQuery->sum('dons.montant'),
                'montant_moyen' => $resumeQuery->avg('dons.montant') ?: 0,
                'don_maximum' => $resumeQuery->max('dons.montant') ?: 0,
                'don_minimum' => $resumeQuery->min('dons.montant') ?: 0,
            ],
            'par_devise' => $parDeviseQuery->selectRaw('dons.devise, SUM(dons.montant) as total, COUNT(*) as nombre, AVG(dons.montant) as moyenne')
                ->groupBy('dons.devise')
                ->orderBy('total', 'desc')
                ->get(),
            'par_operateur' => $parOperateurQuery->join('parametres_dons', 'dons.parametre_fond_id', '=', 'parametres_dons.id')
                ->selectRaw('parametres_dons.operateur, SUM(dons.montant) as total, COUNT(dons.id) as nombre')
                ->groupBy('parametres_dons.operateur')
                ->orderBy('total', 'desc')
                ->get(),
            'par_type_paiement' => $parTypePaiementQuery->join('parametres_dons', 'dons.parametre_fond_id', '=', 'parametres_dons.id')
                ->selectRaw('parametres_dons.type, SUM(dons.montant) as total, COUNT(dons.id) as nombre')
                ->groupBy('parametres_dons.type')
                ->orderBy('total', 'desc')
                ->get(),
            'evolution_mensuelle' => Don::selectRaw('
                                        EXTRACT(YEAR FROM dons.created_at) as annee,
                                        EXTRACT(MONTH FROM dons.created_at) as mois,
                                        SUM(dons.montant) as total,
                                        COUNT(*) as nombre
                                    ')
                ->whereYear('dons.created_at', '>=', now()->subYear()->year)
                ->groupBy('annee', 'mois')
                ->orderBy('annee', 'desc')
                ->orderBy('mois', 'desc')
                ->limit(12)
                ->get(),
            'top_donateurs' => $topDonateursQuery->selectRaw('
                                    CONCAT(dons.prenom_donateur, \' \', dons.nom_donateur) as nom_complet,
                                    dons.telephone_1,
                                    SUM(dons.montant) as total_donne,
                                    COUNT(*) as nombre_dons,
                                    MAX(dons.created_at) as dernier_don
                                ')
                ->groupBy('dons.nom_donateur', 'dons.prenom_donateur', 'dons.telephone_1')
                ->orderBy('total_donne', 'desc')
                ->limit(10)
                ->get(),
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $statistiques,
                'periode' => $periode
            ]);
        }

        return view('components.private.dons.statistiques', compact('statistiques', 'periode'));
    }


    /**
     * Recherche avancée de dons.
     */
    public function rechercheAvancee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom_donateur' => 'nullable|string|max:100',
            'prenom_donateur' => 'nullable|string|max:100',
            'telephone' => 'nullable|string|max:20',
            'montant_min' => 'nullable|numeric|min:0',
            'montant_max' => 'nullable|numeric|min:0',
            'devise' => 'nullable|string|max:10',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'operateur' => 'nullable|string|max:50',
            'type_paiement' => 'nullable|in:' . implode(',', ParametreDon::TYPES),
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

        $query = Don::with(['parametreDon']);

        // Filtres de recherche
        if ($request->filled('nom_donateur')) {
            $query->where('nom_donateur', 'like', '%' . $request->nom_donateur . '%');
        }

        if ($request->filled('prenom_donateur')) {
            $query->where('prenom_donateur', 'like', '%' . $request->prenom_donateur . '%');
        }

        if ($request->filled('telephone')) {
            $query->where(function ($q) use ($request) {
                $q->where('telephone_1', 'like', '%' . $request->telephone . '%')
                    ->orWhere('telephone_2', 'like', '%' . $request->telephone . '%');
            });
        }

        if ($request->filled('montant_min')) {
            $query->montantMinimum($request->montant_min);
        }

        if ($request->filled('montant_max')) {
            $query->montantMaximum($request->montant_max);
        }

        if ($request->filled('devise')) {
            $query->parDevise($request->devise);
        }

        if ($request->filled(['date_debut', 'date_fin'])) {
            $query->whereBetween('created_at', [
                $request->date_debut . ' 00:00:00',
                $request->date_fin . ' 23:59:59'
            ]);
        }

        if ($request->filled('operateur')) {
            $query->whereHas('parametreDon', function ($q) use ($request) {
                $q->where('operateur', 'like', '%' . $request->operateur . '%');
            });
        }

        if ($request->filled('type_paiement')) {
            $query->whereHas('parametreDon', function ($q) use ($request) {
                $q->where('type', $request->type_paiement);
            });
        }

        $dons = $query->latest()->paginate(20);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $dons->items(),
                'pagination' => [
                    'current_page' => $dons->currentPage(),
                    'last_page' => $dons->lastPage(),
                    'per_page' => $dons->perPage(),
                    'total' => $dons->total(),
                    'has_more' => $dons->hasMorePages()
                ],
                'filtres_appliques' => $request->only([
                    'nom_donateur',
                    'prenom_donateur',
                    'telephone',
                    'montant_min',
                    'montant_max',
                    'devise',
                    'date_debut',
                    'date_fin',
                    'operateur',
                    'type_paiement'
                ])
            ]);
        }

        return view('components.private.dons.recherche-avancee', compact('dons'));
    }


    /**
     * Tableau de bord des dons avec métriques en temps réel.
     */
    public function dashboard(Request $request)
    {
        // Statistiques générales
        $statistiques = [
            'aujourd_hui' => [
                'total_dons' => Don::whereDate('created_at', today())->count(),
                'montant_total' => Don::whereDate('created_at', today())->sum('montant'),
                'montant_moyen' => Don::whereDate('created_at', today())->avg('montant') ?: 0,
            ],
            'cette_semaine' => [
                'total_dons' => Don::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'montant_total' => Don::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('montant'),
                'montant_moyen' => Don::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->avg('montant') ?: 0,
            ],
            'ce_mois' => [
                'total_dons' => Don::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
                'montant_total' => Don::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('montant'),
                'montant_moyen' => Don::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->avg('montant') ?: 0,
            ],
            'cette_annee' => [
                'total_dons' => Don::whereYear('created_at', now()->year)->count(),
                'montant_total' => Don::whereYear('created_at', now()->year)->sum('montant'),
                'montant_moyen' => Don::whereYear('created_at', now()->year)->avg('montant') ?: 0,
            ],
        ];

        // Évolution des dons (30 derniers jours)
        $evolution = Don::selectRaw('DATE(created_at) as date, COUNT(*) as total, SUM(montant) as montant')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top opérateurs
        $topOperateurs = Don::join('parametres_dons', 'dons.parametre_fond_id', '=', 'parametres_dons.id')
            ->selectRaw('parametres_dons.operateur, COUNT(dons.id) as nombre, SUM(dons.montant) as total')
            ->groupBy('parametres_dons.operateur')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Derniers dons
        $derniersDons = Don::with(['parametreDon'])
            ->latest()
            ->limit(10)
            ->get();

        // Répartition par devise
        $parDevise = Don::selectRaw('devise, COUNT(*) as nombre, SUM(montant) as total, AVG(montant) as moyenne')
            ->groupBy('devise')
            ->orderBy('total', 'desc')
            ->get();

        // Objectifs (exemple avec des valeurs par défaut)
        $objectifs = [
            'mensuel' => [
                'objectif' => 100000, // À configurer selon vos besoins
                'atteint' => $statistiques['ce_mois']['montant_total'],
                'pourcentage' => $statistiques['ce_mois']['montant_total'] > 0 ? round(($statistiques['ce_mois']['montant_total'] / 100000) * 100, 2) : 0,
            ],
            'annuel' => [
                'objectif' => 1000000, // À configurer selon vos besoins
                'atteint' => $statistiques['cette_annee']['montant_total'],
                'pourcentage' => $statistiques['cette_annee']['montant_total'] > 0 ? round(($statistiques['cette_annee']['montant_total'] / 1000000) * 100, 2) : 0,
            ],
        ];

        $dashboard = compact('statistiques', 'evolution', 'topOperateurs', 'derniersDons', 'parDevise', 'objectifs');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $dashboard
            ]);
        }

        return view('components.private.dons.dashboard', $dashboard);
    }


    /**
     * Dupliquer un don (pour faciliter la saisie de dons similaires).
     */
    public function dupliquer(Request $request, Don $don)
    {
        if ($request->expectsJson()) {
            // Retourner les données du don sans l'ID et la preuve pour duplication côté client
            $donneesACopier = $don->only([
                'parametre_fond_id',
                'nom_donateur',
                'prenom_donateur',
                'telephone_1',
                'telephone_2',
                'devise'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Données du don prêtes pour duplication',
                'data' => $donneesACopier
            ]);
        }

        // Pour la version web, rediriger vers le formulaire de création avec les données pré-remplies
        return redirect()->route('private.dons.create')
            ->withInput($don->only([
                'parametre_fond_id',
                'nom_donateur',
                'prenom_donateur',
                'telephone_1',
                'telephone_2',
                'devise'
            ]))
            ->with('info', 'Formulaire pré-rempli avec les données du don sélectionné');
    }


    /**
     * Afficher les dons d'un donateur spécifique.
     */
    public function parDonateur(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom_donateur' => 'required|string|max:100',
            'prenom_donateur' => 'required|string|max:100',
            'telephone_1' => 'nullable|string|max:20',
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

        $query = Don::where('nom_donateur', $request->nom_donateur)
            ->where('prenom_donateur', $request->prenom_donateur);

        if ($request->filled('telephone_1')) {
            $query->where('telephone_1', $request->telephone_1);
        }

        $dons = $query->with(['parametreDon'])
            ->latest()
            ->paginate(15);

        // Statistiques du donateur
        $statistiques = [
            'total_dons' => $dons->total(),
            'montant_total' => $query->sum('montant'),
            'montant_moyen' => $query->avg('montant') ?: 0,
            'premier_don' => $query->oldest()->first(),
            'dernier_don' => $query->latest()->first(),
            'par_devise' => $query->selectRaw('devise, SUM(montant) as total, COUNT(*) as nombre')
                ->groupBy('devise')
                ->orderBy('total', 'desc')
                ->get(),
        ];

        $donateur = [
            'nom_complet' => trim($request->prenom_donateur . ' ' . $request->nom_donateur),
            'nom_donateur' => $request->nom_donateur,
            'prenom_donateur' => $request->prenom_donateur,
            'telephone_1' => $request->telephone_1,
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $dons->items(),
                'donateur' => $donateur,
                'statistiques' => $statistiques,
                'pagination' => [
                    'current_page' => $dons->currentPage(),
                    'last_page' => $dons->lastPage(),
                    'per_page' => $dons->perPage(),
                    'total' => $dons->total(),
                    'has_more' => $dons->hasMorePages()
                ]
            ]);
        }

        return view('components.private.dons.par-donateur', compact('dons', 'donateur', 'statistiques'));
    }


    /**
     * API publique pour afficher les statistiques de dons (sans données sensibles).
     */
    public function statistiquesPubliques(Request $request)
    {
        // Statistiques publiques sans données sensibles
        $statistiques = [
            'total_dons_collectes' => Don::count(),
            'montant_total_collecte' => Don::sum('montant'),
            'par_devise' => Don::selectRaw('devise, SUM(montant) as total')
                ->groupBy('devise')
                ->orderBy('total', 'desc')
                ->get()
                ->map(function ($item) {
                    return [
                        'devise' => $item->devise,
                        'total' => round($item->total, 2),
                        'devise_libelle' => Don::DEVISES[$item->devise] ?? $item->devise
                    ];
                }),
            'evolution_mensuelle' => Don::selectRaw('
                                        YEAR(created_at) as annee,
                                        MONTH(created_at) as mois,
                                        COUNT(*) as nombre_dons,
                                        SUM(montant) as montant_total
                                    ')
                ->whereYear('created_at', '>=', now()->subYear()->year)
                ->groupBy('annee', 'mois')
                ->orderBy('annee', 'desc')
                ->orderBy('mois', 'desc')
                ->limit(12)
                ->get()
                ->map(function ($item) {
                    return [
                        'periode' => $item->annee . '-' . str_pad($item->mois, 2, '0', STR_PAD_LEFT),
                        'nombre_dons' => $item->nombre_dons,
                        'montant_total' => round($item->montant_total, 2)
                    ];
                }),
            'derniere_mise_a_jour' => now()->toDateTimeString(),
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $statistiques
            ]);
        }

        return view('components.private.dons.statistiques-publiques', compact('statistiques'));
    }


    /**
     * Rapport personnalisé de dons.
     */
    public function rapportPersonnalise(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'grouper_par' => 'nullable|in:jour,semaine,mois,operateur,devise,type_paiement',
            'inclure_details' => 'boolean',
        ], [
            'date_debut.required' => 'La date de début est obligatoire',
            'date_fin.required' => 'La date de fin est obligatoire',
            'date_fin.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début',
            'grouper_par.in' => 'Le critère de regroupement n\'est pas valide',
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

        $query = Don::with(['parametreDon'])
            ->whereBetween('created_at', [
                $request->date_debut . ' 00:00:00',
                $request->date_fin . ' 23:59:59'
            ]);

        // Données de base
        $rapport = [
            'periode' => [
                'debut' => $request->date_debut,
                'fin' => $request->date_fin,
            ],
            'resume' => [
                'total_dons' => $query->count(),
                'montant_total' => $query->sum('montant'),
                'montant_moyen' => $query->avg('montant') ?: 0,
                'don_minimum' => $query->min('montant') ?: 0,
                'don_maximum' => $query->max('montant') ?: 0,
            ],
        ];

        // Regroupement selon le critère choisi
        if ($request->filled('grouper_par')) {
            switch ($request->grouper_par) {
                case 'jour':
                    $rapport['groupement'] = $query->selectRaw('DATE(created_at) as periode, COUNT(*) as nombre, SUM(montant) as total')
                        ->groupBy('periode')
                        ->orderBy('periode')
                        ->get();
                    break;
                case 'mois':
                    $rapport['groupement'] = $query->selectRaw('YEAR(created_at) as annee, MONTH(created_at) as mois, COUNT(*) as nombre, SUM(montant) as total')
                        ->groupBy('annee', 'mois')
                        ->orderBy('annee')
                        ->orderBy('mois')
                        ->get();
                    break;
                case 'operateur':
                    $rapport['groupement'] = $query->join('parametres_dons', 'dons.parametre_fond_id', '=', 'parametres_dons.id')
                        ->selectRaw('parametres_dons.operateur, COUNT(dons.id) as nombre, SUM(dons.montant) as total')
                        ->groupBy('parametres_dons.operateur')
                        ->orderBy('total', 'desc')
                        ->get();
                    break;
                case 'devise':
                    $rapport['groupement'] = $query->selectRaw('devise, COUNT(*) as nombre, SUM(montant) as total, AVG(montant) as moyenne')
                        ->groupBy('devise')
                        ->orderBy('total', 'desc')
                        ->get();
                    break;
                case 'type_paiement':
                    $rapport['groupement'] = $query->join('parametres_dons', 'dons.parametre_fond_id', '=', 'parametres_dons.id')
                        ->selectRaw('parametres_dons.type, COUNT(dons.id) as nombre, SUM(dons.montant) as total')
                        ->groupBy('parametres_dons.type')
                        ->orderBy('total', 'desc')
                        ->get();
                    break;
            }
        }

        // Inclure les détails si demandé
        if ($request->boolean('inclure_details', false)) {
            $rapport['details'] = $query->orderBy('created_at', 'desc')->get();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $rapport
            ]);
        }

        return view('components.private.dons.rapport-personnalise', compact('rapport'));
    }
}
