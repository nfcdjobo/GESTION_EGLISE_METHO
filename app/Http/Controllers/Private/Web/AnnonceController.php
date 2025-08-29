<?php

namespace App\Http\Controllers\Private\Web;

use App\Models\User;
use App\Models\Annonce;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class AnnonceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:create,App\Models\Annonce')->only(['create', 'store']);
        $this->middleware('can:update,annonce')->only(['edit', 'update']);
        $this->middleware('can:delete,annonce')->only(['destroy']);
    }

    /**
     * Affichage de la liste des annonces avec filtres
     */
    public function index(Request $request): View
    {
        $query = Annonce::with(['contactPrincipal', 'auteur']);

        // Filtres
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('type_annonce')) {
            $query->parType($request->type_annonce);
        }

        if ($request->filled('audience_cible')) {
            $query->parAudience($request->audience_cible);
        }

        if ($request->filled('niveau_priorite')) {
            $query->where('niveau_priorite', $request->niveau_priorite);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'ILIKE', "%{$search}%")->orWhere('contenu', 'ILIKE', "%{$search}%");
            });
        }

        // Tri par défaut par priorité puis date de publication
        $annonces = $query->trieesParPriorite()->paginate(15);

        return view('components.private.annonces.index', [
            'annonces' => $annonces,
            'filtres' => $request->only(['statut', 'type_annonce', 'audience_cible', 'niveau_priorite', 'search']),
            'typesAnnonces' => Annonce::getTypesAnnonces(),
            'niveauxPriorite' => Annonce::getNiveauxPriorite(),
            'audiencesCibles' => Annonce::getAudiencesCibles(),
            'statuts' => Annonce::getStatuts(),
        ]);
    }

    /**
     * Affichage des annonces actives (publiques)
     */
    public function annoncesActives(Request $request): View|JsonResponse
    {
        $query = Annonce::actives()->with(['contactPrincipal', 'auteur'])->trieesParPriorite();

        if ($request->filled('type_annonce')) {
            $query->parType($request->type_annonce);
        }

        if ($request->filled('audience_cible')) {
            $query->parAudience($request->audience_cible);
        }

        $annonces = $query->get();

        if ($request->expectsJson()) {
            return response()->json($annonces);
        }

        return view('components.private.annonces.publiques', compact('annonces'));
    }

    /**
     * Formulaire de création d'une nouvelle annonce
     */
    public function create(): View
    {
        $contacts = User::select('id', 'nom', 'prenom', 'email')->orderBy('nom')->get();

        return view('components.private.annonces.create', [
            'contacts' => $contacts,
            'typesAnnonces' => Annonce::getTypesAnnonces(),
            'niveauxPriorite' => Annonce::getNiveauxPriorite(),
            'audiencesCibles' => Annonce::getAudiencesCibles(),
        ]);
    }

    /**
     * Sauvegarde d'une nouvelle annonce
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:200',
            'contenu' => 'required|string',
            'type_annonce' => ['required', Rule::in(['evenement', 'administrative', 'pastorale', 'urgence', 'information'])],
            'niveau_priorite' => ['nullable', Rule::in(['normal', 'important', 'urgent'])],
            'audience_cible' => ['nullable', Rule::in(['tous', 'membres', 'leadership', 'jeunes'])],
            'date_evenement' => 'nullable|date|after_or_equal:today',
            'expire_le' => 'nullable|date|after:today',
            'contact_principal_id' => 'nullable|exists:users,id',
            'lieu_evenement' => 'nullable|string|max:255',
            'afficher_site_web' => 'boolean',
            'annoncer_culte' => 'boolean',
            'publier_maintenant' => 'boolean',
        ]);

        $validated['cree_par'] = Auth::id();

        // Publication immédiate si demandée
        if ($request->boolean('publier_maintenant')) {
            $validated['statut'] = 'publiee';
            $validated['publie_le'] = now();
        }

        $annonce = Annonce::create($validated);

        return redirect()->route('private.annonces.show', $annonce)->with('success', 'Annonce créée avec succès.');
    }

    /**
     * Affichage d'une annonce spécifique
     */
    public function show(Annonce $annonce): View
    {
        $annonce->load(['contactPrincipal', 'auteur']);

        return view('components.private.annonces.show', compact('annonce'));
    }

    /**
     * Formulaire d'édition d'une annonce
     */
    public function edit(Annonce $annonce): View
    {
        $contacts = User::select('id', 'nom', 'prenom', 'email')->orderBy('nom')->get();

        return view('components.private.annonces.edit', [
            'annonce' => $annonce,
            'contacts' => $contacts,
            'typesAnnonces' => Annonce::getTypesAnnonces(),
            'niveauxPriorite' => Annonce::getNiveauxPriorite(),
            'audiencesCibles' => Annonce::getAudiencesCibles(),
        ]);
    }

    /**
     * Mise à jour d'une annonce
     */
    public function update(Request $request, Annonce $annonce): RedirectResponse
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:200',
            'contenu' => 'required|string',
            'type_annonce' => ['required', Rule::in(['evenement', 'administrative', 'pastorale', 'urgence', 'information'])],
            'niveau_priorite' => ['nullable', Rule::in(['normal', 'important', 'urgent'])],
            'audience_cible' => ['nullable', Rule::in(['tous', 'membres', 'leadership', 'jeunes'])],
            'date_evenement' => 'nullable|date',
            'expire_le' => 'nullable|date|after:today',
            'contact_principal_id' => 'nullable|exists:users,id',
            'lieu_evenement' => 'nullable|string|max:255',
            'afficher_site_web' => 'boolean',
            'annoncer_culte' => 'boolean',
        ]);

        $annonce->update($validated);

        return redirect()->route('private.annonces.show', $annonce)->with('success', 'Annonce mise à jour avec succès.');
    }

    /**
     * Suppression d'une annonce
     */
    public function destroy(Annonce $annonce): RedirectResponse
    {
        $annonce->delete();

        return redirect()->route('private.annonces.index')->with('success', 'Annonce supprimée avec succès.');
    }


    /**
     * Publication d'une annonce
     */
    public function publier(Request $request, Annonce $annonce)
    {
        if ($annonce->publier()) {
             if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Annonce publiée avec succès.'
                ]);
            }
            return back()->with('success', 'Annonce publiée avec succès.');
        }

        return back()->with('error', 'Impossible de publier cette annonce.');
    }

    /**
     * Archivage d'une annonce
     */
    public function archiver(Request $request, Annonce $annonce)
    {
        if ($annonce->archiver()) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Annonce publiée avec succès.'
                ]);
            }
            return back()->with('success', 'Annonce publiée avec succès.');
            return back()->with('success', 'Annonce archivée avec succès.');
        }

        return back()->with('error', 'Impossible d\'archiver cette annonce.');
    }

    /**
     * Duplication d'une annonce
     */
    public function dupliquer(Annonce $annonce): RedirectResponse
    {
        $nouvelleAnnonce = $annonce->dupliquer();

        return redirect()->route('private.annonces.edit', $nouvelleAnnonce)->with('success', 'Annonce dupliquée avec succès. Vous pouvez maintenant la modifier.');
    }

    /**
     * Annonces pour le culte (API)
     */
    public function pourCulte(): JsonResponse
    {
        $annonces = Annonce::actives()->pourCulte()->with(['contactPrincipal'])->trieesParPriorite()->get();

        return response()->json($annonces);
    }

    /**
     * Annonces urgentes (API)
     */
    public function urgentes(): JsonResponse
    {
        $annonces = Annonce::actives()->urgentes()->with(['contactPrincipal', 'auteur'])->orderBy('publie_le', 'desc')->get();

        return response()->json($annonces);
    }

    /**
     * Statistiques des annonces (API)
     */
    public function statistiques(): JsonResponse
    {
        $stats = [
            'total' => Annonce::count(),
            'actives' => Annonce::actives()->count(),
            'brouillons' => Annonce::where('statut', 'brouillon')->count(),
            'expirees' => Annonce::where('statut', 'expiree')->count(),
            'urgentes' => Annonce::urgentes()->actives()->count(),
            'par_type' => Annonce::selectRaw('type_annonce, COUNT(*) as total')->groupBy('type_annonce')->pluck('total', 'type_annonce'),
        ];

        return response()->json($stats);
    }


    /**
     * Déterminer si la requête est une requête API
     */
    private function isApiRequest(Request $request)
    {
        return $request->wantsJson() ||
               $request->expectsJson() ||
               $request->is('api/*') ||
               $request->header('Accept') === 'application/json' ||
               $request->ajax();
    }
}
