<?php

namespace App\Http\Controllers\Private\Web;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function __construct()
{
    $this->middleware('auth');
    $this->middleware('permission:contacts.read')->only(['index', 'show', 'statistics', 'searchNearby']);
    $this->middleware('permission:contacts.create')->only(['create', 'store']);
    $this->middleware('permission:contacts.update')->only(['edit', 'update', 'verify']);
    $this->middleware('permission:contacts.delete')->only(['destroy']);
    $this->middleware('permission:contacts.manage')->only(['bulkActions']);
    $this->middleware('permission:contacts.export')->only(['export']);
}

    /**
     * Afficher la liste des contacts
     */
    public function index(Request $request)
    {
        try {
            $query = Contact::query();

            // Filtres
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nom_eglise', 'like', "%{$search}%")
                      ->orWhere('denomination', 'like', "%{$search}%")
                      ->orWhere('ville', 'like', "%{$search}%")
                      ->orWhere('telephone_principal', 'like', "%{$search}%")
                      ->orWhere('email_principal', 'like', "%{$search}%")
                      ->orWhere('pasteur_principal', 'like', "%{$search}%");
                });
            }

            if ($request->filled('type_contact')) {
                $query->where('type_contact', $request->type_contact);
            }

            if ($request->filled('ville')) {
                $query->where('ville', $request->ville);
            }

            if ($request->filled('denomination')) {
                $query->where('denomination', $request->denomination);
            }

            if ($request->filled('visible_public')) {
                $query->where('visible_public', $request->visible_public === 'true');
            }

            if ($request->filled('verifie')) {
                $query->where('verifie', $request->verifie === 'true');
            }

            if ($request->filled('avec_geo')) {
                if ($request->avec_geo === 'true') {
                    $query->avecGeo();
                }
            }

            if ($request->filled('avec_reseaux_sociaux')) {
                if ($request->avec_reseaux_sociaux === 'true') {
                    $query->avecReseauxSociaux();
                }
            }

            // Tri
            $sortField = $request->get('sort', 'nom_eglise');
            $sortDirection = $request->get('direction', 'asc');

            $allowedSorts = ['nom_eglise', 'type_contact', 'ville', 'denomination', 'created_at', 'derniere_verification'];
            if (in_array($sortField, $allowedSorts)) {
                $query->orderBy($sortField, $sortDirection);
            } else {
                $query->orderBy('nom_eglise', 'asc');
            }

            // Pagination
            $perPage = $request->get('per_page', 20);
            $contacts = $query->with(['responsableContact:id,nom,prenom', 'createur:id,nom,prenom', 'modificateur:id,nom,prenom'])
                             ->paginate($perPage)
                             ->withQueryString();

            // Données pour les filtres
            $filterData = [
                'types_contact' => [
                    'principal', 'administratif', 'pastoral', 'urgence', 'jeunesse',
                    'femmes', 'hommes', 'enfants', 'technique', 'media', 'finance', 'social'
                ],
                'villes' => Contact::distinct()->whereNotNull('ville')->pluck('ville')->filter()->sort()->values(),
                'denominations' => Contact::distinct()->whereNotNull('denomination')->pluck('denomination')->filter()->sort()->values(),
            ];

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'contacts' => $contacts,
                        'filters' => $filterData
                    ],
                    'message' => 'Contacts récupérés avec succès'
                ]);
            }

            return view('components.private.contacts.index', array_merge(compact('contacts'), $filterData));

        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération des contacts',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de la récupération des contacts']);
        }
    }

    /**
     * Afficher le formulaire de création
     */
    public function create(Request $request)
    {
        try {
            $filterData = [
                'types_contact' => [
                    'principal', 'administratif', 'pastoral', 'urgence', 'jeunesse',
                    'femmes', 'hommes', 'enfants', 'technique', 'media', 'finance', 'social'
                ],
                'denominations' => Contact::distinct()->whereNotNull('denomination')->pluck('denomination')->filter()->sort()->values(),
                'villes' => Contact::distinct()->whereNotNull('ville')->pluck('ville')->filter()->sort()->values(),
                'responsables' => User::select('id', 'nom', 'prenom')->orderBy('nom')->get(),
            ];

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'data' => $filterData,
                    'message' => 'Données pour création récupérées avec succès'
                ]);
            }

            return view('components.private.contacts.create', $filterData);

        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération des données',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de la récupération des données']);
        }
    }

    /**
     * Enregistrer un nouveau contact
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Informations de base
            'nom_eglise' => 'required|string|max:200',
            'denomination' => 'nullable|string|max:100',
            'description_courte' => 'nullable|string',
            'mission_vision' => 'nullable|string',
            'type_contact' => 'required|in:principal,administratif,pastoral,urgence,jeunesse,femmes,hommes,enfants,technique,media,finance,social',

            // Coordonnées téléphoniques
            'telephone_principal' => 'nullable|string|max:20',
            'telephone_secondaire' => 'nullable|string|max:20',
            'telephone_urgence' => 'nullable|string|max:20',
            'fax' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',

            // Emails
            'email_principal' => 'nullable|email',
            'email_administratif' => 'nullable|email',
            'email_pastoral' => 'nullable|email',
            'email_info' => 'nullable|email',
            'email_presse' => 'nullable|email',

            // Adresse
            'adresse_complete' => 'nullable|string',
            'rue' => 'nullable|string|max:200',
            'quartier' => 'nullable|string|max:100',
            'ville' => 'nullable|string|max:100',
            'commune' => 'nullable|string|max:100',
            'code_postal' => 'nullable|string|max:10',
            'region' => 'nullable|string|max:100',
            'pays' => 'nullable|string|max:100',

            // Géolocalisation
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'indications_acces' => 'nullable|string',
            'points_repere' => 'nullable|string',

            // Réseaux sociaux
            'facebook_url' => 'nullable|url',
            'facebook_handle' => 'nullable|string',
            'instagram_url' => 'nullable|url',
            'instagram_handle' => 'nullable|string',
            'youtube_url' => 'nullable|url',
            'youtube_handle' => 'nullable|string',
            'twitter_url' => 'nullable|url',
            'twitter_handle' => 'nullable|string',

            // Site web
            'site_web_principal' => 'nullable|url',
            'site_web_secondaire' => 'nullable|url',

            // Horaires
            'horaires_bureau' => 'nullable|array',
            'horaires_cultes' => 'nullable|array',
            'disponible_24h' => 'boolean',

            // Leadership
            'pasteur_principal' => 'nullable|string|max:100',
            'telephone_pasteur' => 'nullable|string|max:20',
            'email_pasteur' => 'nullable|email',

            // Informations bancaires
            'iban_dons' => 'nullable|string',
            'mobile_money_orange' => 'nullable|string|max:20',
            'mobile_money_mtn' => 'nullable|string|max:20',

            // Paramètres
            'visible_public' => 'boolean',
            'responsable_contact_id' => 'nullable|exists:users,id',

            // Capacité
            'capacite_accueil' => 'nullable|integer|min:0',
            'nombre_membres' => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $contactData = array_merge($validated, [
                'cree_par' => auth()->id(),
                'modifie_par' => auth()->id(),
                'derniere_mise_a_jour' => now()->toDateString(),
            ]);

            $contact = Contact::create($contactData);

            // Géocoder l'adresse si nécessaire
            if (!$contact->latitude && !$contact->longitude && $contact->adresse_complete) {
                $this->geocodeAddress($contact);
            }

            DB::commit();

            $contact->load(['responsableContact', 'createur', 'modificateur']);

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'data' => $contact,
                    'message' => "Contact '{$contact->nom_eglise}' créé avec succès"
                ], 201);
            }

            return redirect()
                ->route('private.contacts.show', $contact)
                ->with('success', "Contact '{$contact->nom_eglise}' créé avec succès!");

        } catch (\Exception $e) {
            DB::rollBack();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création du contact',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    /**
     * Afficher un contact
     */
    public function show(Request $request, Contact $contact)
    {
        try {
            $contact->load(['responsableContact', 'createur', 'modificateur']);

            // Statistiques et informations complémentaires
            $stats = [
                'completude' => $this->calculateCompleteness($contact),
                'derniere_verification' => $contact->derniere_verification,
                'derniere_modification' => $contact->updated_at,
                'est_complet' => $contact->isComplet(),
                'ouvert_maintenant' => $contact->isOuvertMaintenant(),
                'horaires_aujourd_hui' => $contact->getHorairesAujourdhui(),
            ];

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'contact' => $contact,
                        'statistics' => $stats,
                        'reseaux_sociaux' => $contact->reseaux_sociaux,
                        'mobile_money' => $contact->mobile_money,
                    ],
                    'message' => 'Contact récupéré avec succès'
                ]);
            }

            return view('components.private.contacts.show', compact('contact', 'stats'));

        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération du contact',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de la récupération du contact']);
        }
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Request $request, Contact $contact)
    {
        try {
            $filterData = [
                'types_contact' => [
                    'principal', 'administratif', 'pastoral', 'urgence', 'jeunesse',
                    'femmes', 'hommes', 'enfants', 'technique', 'media', 'finance', 'social'
                ],
                'denominations' => Contact::distinct()->whereNotNull('denomination')->pluck('denomination')->filter()->sort()->values(),
                'villes' => Contact::distinct()->whereNotNull('ville')->pluck('ville')->filter()->sort()->values(),
                'responsables' => User::select('id', 'nom', 'prenom')->orderBy('nom')->get(),
            ];

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'data' => array_merge([
                        'contact' => $contact
                    ], $filterData),
                    'message' => 'Données pour édition récupérées avec succès'
                ]);
            }

            return view('components.private.contacts.edit', array_merge(compact('contact'), $filterData));

        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération des données',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de la récupération des données']);
        }
    }

    /**
     * Mettre à jour un contact
     */
    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            // Mêmes règles que pour store()
            'nom_eglise' => 'required|string|max:200',
            'denomination' => 'nullable|string|max:100',
            'description_courte' => 'nullable|string',
            'mission_vision' => 'nullable|string',
            'type_contact' => 'required|in:principal,administratif,pastoral,urgence,jeunesse,femmes,hommes,enfants,technique,media,finance,social',
            'telephone_principal' => 'nullable|string|max:20',
            'telephone_secondaire' => 'nullable|string|max:20',
            'telephone_urgence' => 'nullable|string|max:20',
            'email_principal' => 'nullable|email',
            'email_administratif' => 'nullable|email',
            'email_pastoral' => 'nullable|email',
            'adresse_complete' => 'nullable|string',
            'rue' => 'nullable|string|max:200',
            'quartier' => 'nullable|string|max:100',
            'ville' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'site_web_principal' => 'nullable|url',
            'horaires_bureau' => 'nullable|array',
            'horaires_cultes' => 'nullable|array',
            'pasteur_principal' => 'nullable|string|max:100',
            'telephone_pasteur' => 'nullable|string|max:20',
            'email_pasteur' => 'nullable|email',
            'iban_dons' => 'nullable|string',
            'visible_public' => 'boolean',
            'responsable_contact_id' => 'nullable|exists:users,id',
            'capacite_accueil' => 'nullable|integer|min:0',
            'nombre_membres' => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $oldAddress = $contact->adresse_complete;

            $contact->update(array_merge($validated, [
                'modifie_par' => auth()->id(),
                'derniere_mise_a_jour' => now()->toDateString(),
            ]));

            // Géocoder si l'adresse a changé
            if ($contact->adresse_complete !== $oldAddress && $contact->adresse_complete) {
                $this->geocodeAddress($contact);
            }

            DB::commit();

            $contact->load(['responsableContact', 'createur', 'modificateur']);

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'data' => $contact,
                    'message' => "Contact '{$contact->nom_eglise}' mis à jour avec succès"
                ]);
            }

            return redirect()
                ->route('private.contacts.show', $contact)
                ->with('success', "Contact '{$contact->nom_eglise}' mis à jour avec succès!");

        } catch (\Exception $e) {
            DB::rollBack();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour du contact',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * Supprimer un contact
     */
    public function destroy(Request $request, Contact $contact)
    {
        try {
            DB::beginTransaction();

            $nomEglise = $contact->nom_eglise;
            $contact->delete();

            DB::commit();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'message' => "Contact '$nomEglise' supprimé avec succès"
                ]);
            }

            return redirect()
                ->route('private.contacts.index')
                ->with('success', "Contact '$nomEglise' supprimé avec succès!");

        } catch (\Exception $e) {
            DB::rollBack();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression du contact',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Vérifier un contact
     */
    public function verify(Request $request, Contact $contact)
    {
        try {
            $contact->marquerVerifie(auth()->id());

            return response()->json([
                'success' => true,
                'data' => [
                    'verifie' => $contact->verifie,
                    'derniere_verification' => $contact->derniere_verification,
                ],
                'message' => 'Contact vérifié avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la vérification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actions en lot
     */
    public function bulkActions(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:verify,unverify,activate,deactivate,delete',
            'contact_ids' => 'required|array',
            'contact_ids.*' => 'exists:contacts,id'
        ]);

        try {
            DB::beginTransaction();

            $contacts = Contact::whereIn('id', $validated['contact_ids']);
            $count = $contacts->count();

            switch ($validated['action']) {
                case 'verify':
                    $contacts->update([
                        'verifie' => true,
                        'derniere_verification' => now(),
                        'modifie_par' => auth()->id()
                    ]);
                    $message = "{$count} contact(s) vérifié(s) avec succès";
                    break;

                case 'unverify':
                    $contacts->update([
                        'verifie' => false,
                        'modifie_par' => auth()->id()
                    ]);
                    $message = "{$count} contact(s) non vérifié(s) avec succès";
                    break;

                case 'activate':
                    $contacts->update([
                        'visible_public' => true,
                        'modifie_par' => auth()->id()
                    ]);
                    $message = "{$count} contact(s) activé(s) avec succès";
                    break;

                case 'deactivate':
                    $contacts->update([
                        'visible_public' => false,
                        'modifie_par' => auth()->id()
                    ]);
                    $message = "{$count} contact(s) désactivé(s) avec succès";
                    break;

                case 'delete':
                    $contacts->delete();
                    $message = "{$count} contact(s) supprimé(s) avec succès";
                    break;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'affected_count' => $count
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'action en lot',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Recherche géographique
     */
    public function searchNearby(Request $request)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:0.1|max:100', // en km
            'limit' => 'nullable|integer|min:1|max:50'
        ]);

        try {
            $latitude = $validated['latitude'];
            $longitude = $validated['longitude'];
            $radius = $validated['radius'] ?? 10; // 10km par défaut
            $limit = $validated['limit'] ?? 20;

            // Utiliser la formule de distance Haversine
            $contacts = Contact::select('*')
                ->selectRaw(
                    "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance",
                    [$latitude, $longitude, $latitude]
                )
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->where('visible_public', true)
                ->havingRaw('distance < ?', [$radius])
                ->orderBy('distance')
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $contacts,
                'search_params' => [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'radius' => $radius,
                    'found_count' => $contacts->count()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la recherche géographique',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export des contacts
     */
    public function export(Request $request)
    {
        Gate::authorize('export-data', 'contacts');

        try {
            $format = $request->get('format', 'csv');
            $type = $request->get('type', 'all'); // all, public, verified

            $query = Contact::with(['responsableContact']);

            switch ($type) {
                case 'public':
                    $query->where('visible_public', true);
                    break;
                case 'verified':
                    $query->where('verifie', true);
                    break;
            }

            if ($request->filled('ville')) {
                $query->where('ville', $request->ville);
            }

            $contacts = $query->orderBy('nom_eglise')->get();

            switch ($format) {
                case 'json':
                    return response()->json([
                        'success' => true,
                        'data' => $contacts,
                        'total' => $contacts->count(),
                        'exported_at' => now()->toISOString()
                    ]);

                case 'vcf': // vCard format
                    $vcf = $this->generateVCardFormat($contacts);
                    return response($vcf)
                        ->header('Content-Type', 'text/vcard; charset=utf-8')
                        ->header('Content-Disposition', 'attachment; filename="contacts_eglise_' . date('Y-m-d_H-i-s') . '.vcf"');

                default: // CSV
                    $csv = "Nom Église,Type,Dénomination,Téléphone,Email,Adresse,Ville,Site Web,Pasteur,Vérifié,Visible\n";

                    foreach ($contacts as $contact) {
                        $csv .= sprintf(
                            '"%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s"' . "\n",
                            $contact->nom_eglise,
                            $contact->type_contact,
                            $contact->denomination ?? '',
                            $contact->telephone_principal ?? '',
                            $contact->email_principal ?? '',
                            $contact->adresse_complete ?? '',
                            $contact->ville ?? '',
                            $contact->site_web_principal ?? '',
                            $contact->pasteur_principal ?? '',
                            $contact->verifie ? 'Oui' : 'Non',
                            $contact->visible_public ? 'Oui' : 'Non'
                        );
                    }

                    return response($csv)
                        ->header('Content-Type', 'text/csv; charset=utf-8')
                        ->header('Content-Disposition', 'attachment; filename="contacts_eglise_' . date('Y-m-d_H-i-s') . '.csv"');
            }

        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'export',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de l\'export : ' . $e->getMessage());
        }
    }

    /**
     * Statistiques des contacts
     */
    public function statistics(Request $request)
    {
        try {
            // Statistiques générales
            $generalStats = [
                'total_contacts' => Contact::count(),
                'contacts_verifies' => Contact::where('verifie', true)->count(),
                'contacts_publics' => Contact::where('visible_public', true)->count(),
                'contacts_avec_geo' => Contact::whereNotNull('latitude')->whereNotNull('longitude')->count(),
                'contacts_avec_site' => Contact::whereNotNull('site_web_principal')->count(),
                'contacts_avec_reseaux' => Contact::avecReseauxSociaux()->count(),
            ];

            // Répartition par type
            $byType = Contact::selectRaw('type_contact, COUNT(*) as count')
                ->groupBy('type_contact')
                ->orderBy('count', 'desc')
                ->get();

            // Répartition par ville
            $byCity = Contact::selectRaw('ville, COUNT(*) as count')
                ->whereNotNull('ville')
                ->groupBy('ville')
                ->orderBy('count', 'desc')
                ->take(10)
                ->get();

            // Répartition par dénomination
            $byDenomination = Contact::selectRaw('denomination, COUNT(*) as count')
                ->whereNotNull('denomination')
                ->groupBy('denomination')
                ->orderBy('count', 'desc')
                ->take(10)
                ->get();

            // Contacts récents
            $recentContacts = Contact::orderBy('created_at', 'desc')
                ->take(5)
                ->get(['id', 'nom_eglise', 'type_contact', 'ville', 'created_at']);

            // Contacts nécessitant une vérification
            $needVerification = Contact::where('verifie', false)
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get(['id', 'nom_eglise', 'type_contact', 'ville', 'created_at']);

            $stats = [
                'general' => $generalStats,
                'by_type' => $byType,
                'by_city' => $byCity,
                'by_denomination' => $byDenomination,
                'recent' => $recentContacts,
                'need_verification' => $needVerification
            ];

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'data' => $stats,
                    'message' => 'Statistiques récupérées avec succès'
                ]);
            }

            return view('components.private.contacts.statistics', $stats);

        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération des statistiques',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de la récupération des statistiques']);
        }
    }

    /**
     * Géocoder une adresse
     */
    private function geocodeAddress(Contact $contact)
    {
        try {
            // Utiliser un service de géocodage (Google Maps, OpenStreetMap, etc.)
            // Exemple avec un service hypothétique
            $address = urlencode($contact->adresse_complete . ', ' . $contact->ville . ', ' . $contact->pays);

            // Simuler l'appel à un service de géocodage
            // En production, remplacer par un vrai service
            $response = Http::get("https://api.geocoding-service.com/geocode?address={$address}");

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['lat']) && isset($data['lng'])) {
                    $contact->update([
                        'latitude' => $data['lat'],
                        'longitude' => $data['lng']
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Log l'erreur mais ne pas faire échouer la création/modification
            Log::warning('Erreur de géocodage pour le contact ' . $contact->id, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Calculer le pourcentage de complétude
     */
    private function calculateCompleteness(Contact $contact)
    {
        $totalFields = 0;
        $filledFields = 0;

        // Champs obligatoires
        $requiredFields = [
            'nom_eglise', 'telephone_principal', 'email_principal',
            'adresse_complete', 'ville'
        ];

        // Champs optionnels importants
        $optionalFields = [
            'denomination', 'description_courte', 'pasteur_principal',
            'site_web_principal', 'facebook_url', 'latitude', 'longitude'
        ];

        // Vérifier les champs obligatoires
        foreach ($requiredFields as $field) {
            $totalFields++;
            if (!empty($contact->$field)) {
                $filledFields++;
            }
        }

        // Vérifier les champs optionnels
        foreach ($optionalFields as $field) {
            $totalFields++;
            if (!empty($contact->$field)) {
                $filledFields++;
            }
        }

        return round(($filledFields / $totalFields) * 100);
    }

    /**
     * Générer le format vCard
     */
    private function generateVCardFormat($contacts)
    {
        $vcf = '';

        foreach ($contacts as $contact) {
            $vcf .= "BEGIN:VCARD\n";
            $vcf .= "VERSION:3.0\n";
            $vcf .= "FN:{$contact->nom_eglise}\n";
            $vcf .= "ORG:{$contact->nom_eglise}\n";

            if ($contact->telephone_principal) {
                $vcf .= "TEL;TYPE=WORK,VOICE:{$contact->telephone_principal}\n";
            }

            if ($contact->email_principal) {
                $vcf .= "EMAIL;TYPE=WORK:{$contact->email_principal}\n";
            }

            if ($contact->adresse_complete) {
                $vcf .= "ADR;TYPE=WORK:;;{$contact->adresse_complete};{$contact->ville};;{$contact->code_postal};{$contact->pays}\n";
            }

            if ($contact->site_web_principal) {
                $vcf .= "URL:{$contact->site_web_principal}\n";
            }

            $vcf .= "END:VCARD\n";
        }

        return $vcf;
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
