<?php

namespace App\Http\Controllers\Public;

use App\Models\Don;
use App\Models\ParametreDon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class DonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parametresDons = ParametreDon::actifEtPublie()->get();
        return view('components.public.dons.index', compact('parametresDons'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create(ParametreDon $parametreDon)
    {

        // Vérifier que le paramètre est actif et publié
        if (!$parametreDon->estActif() || !$parametreDon->estPublie()) {
            return redirect()->back()->with('error', 'Ce moyen de paiement n\'est pas disponible.');
        }

        return view('components.public.dons.create', compact('parametreDon'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'parametre_fond_id' => 'required|exists:parametres_dons,id',
            'nom_donateur' => 'required|string|max:100',
            'prenom_donateur' => 'required|string|max:100',
            'telephone_1' => 'required|string|max:20',
            'telephone_2' => 'nullable|string|max:20',
            'montant' => 'required|numeric|min:0.01',
            'devise' => 'required|string|max:10',
            'preuve' => 'required|file|mimes:jpg,jpeg,png|max:2048', // 5MB max
        ], [
            'parametre_fond_id.required' => 'Le moyen de paiement est requis.',
            'parametre_fond_id.exists' => 'Le moyen de paiement sélectionné n\'existe pas.',
            'nom_donateur.required' => 'Le nom est requis.',
            'prenom_donateur.required' => 'Le prénom est requis.',
            'telephone_1.required' => 'Le numéro de téléphone est requis.',
            'montant.required' => 'Le montant est requis.',
            'montant.min' => 'Le montant doit être supérieur à 0.',
            'devise.required' => 'La devise est requise.',
            'preuve.required' => 'La preuve de paiement est requise.',
            'preuve.mimes' => 'La preuve doit être un fichier JPG, JPEG, PNG ou PDF.',
            'preuve.max' => 'La preuve ne doit pas dépasser 2MB.',
        ]);

        // Upload du fichier de preuve
        if ($request->hasFile('preuve')) {
            $path = $request->file('preuve')->store('preuves-dons', 'public');
            $validated['preuve'] = $path;
        }

        // Créer le don
        Don::create($validated);

        return redirect()->route('public.donates.index')->with('success', 'Votre don a été enregistré avec succès. Il sera vérifié dans les plus brefs délais.');
    }

}
