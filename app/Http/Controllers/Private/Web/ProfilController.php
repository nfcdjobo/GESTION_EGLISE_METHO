<?php

namespace App\Http\Controllers\Private\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfilController extends Controller
{
    /**
     * Afficher le profil de l'utilisateur connecté
     */
    public function index()
    {
        $user = Auth::user()->load('classe', 'roles');

        return view('components.private.profil.index', compact('user'));
    }

    /**
     * Afficher le formulaire d'édition du profil
     */
    public function edit()
    {
        $user = Auth::user()->load('classe');

        return view('components.private.profil.edit', compact('user'));
    }

    /**
     * Mettre à jour les informations personnelles de l'utilisateur
     */
    public function updateInformations(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            // Informations personnelles de base
            'prenom' => ['required', 'string', 'max:100'],
            'nom' => ['required', 'string', 'max:100'],
            'date_naissance' => ['nullable', 'date', 'before:today', 'after:1900-01-01'],
            'sexe' => ['required', Rule::in(['masculin', 'feminin'])],

            // Informations de contact
            'telephone_1' => ['required', 'string', 'min:8', 'max:20'],
            'telephone_2' => ['nullable', 'string', 'min:8', 'max:20'],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],

            // Adresse
            'adresse_ligne_1' => ['nullable', 'string', 'max:200'],
            'adresse_ligne_2' => ['nullable', 'string', 'max:200'],
            'ville' => ['nullable', 'string', 'max:100'],
            'code_postal' => ['nullable', 'string', 'max:20'],
            'region' => ['nullable', 'string', 'max:100'],
            'pays' => ['nullable', 'string', 'max:50'],

            // Informations familiales
            'statut_matrimonial' => [
                'required',
                Rule::in(['celibataire', 'marie', 'divorce', 'veuf'])
            ],
            'nombre_enfants' => ['required', 'integer', 'min:0', 'max:20'],

            // Informations professionnelles
            'profession' => ['nullable', 'string', 'max:150'],
            'employeur' => ['nullable', 'string', 'max:150'],

            // Contact d'urgence
            'contact_urgence_nom' => ['nullable', 'string', 'max:100'],
            'contact_urgence_telephone' => ['nullable', 'string', 'max:20'],
            'contact_urgence_relation' => ['nullable', 'string', 'max:50'],

            // Photo de profil
            'photo_profil' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ], [
            'prenom.required' => 'Le prénom est obligatoire.',
            'nom.required' => 'Le nom est obligatoire.',
            'sexe.required' => 'Le sexe est obligatoire.',
            'telephone_1.required' => 'Le numéro de téléphone principal est obligatoire.',
            'telephone_1.min' => 'Le numéro de téléphone doit contenir au moins 8 caractères.',
            'email.unique' => 'Cet email est déjà utilisé par un autre utilisateur.',
            'date_naissance.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',
            'nombre_enfants.min' => 'Le nombre d\'enfants ne peut pas être négatif.',
            'nombre_enfants.max' => 'Le nombre d\'enfants ne peut pas dépasser 20.',
            'photo_profil.image' => 'Le fichier doit être une image.',
            'photo_profil.mimes' => 'L\'image doit être au format jpeg, png, jpg ou gif.',
            'photo_profil.max' => 'L\'image ne doit pas dépasser 2 Mo.',
        ]);

        // Gestion de l'upload de la photo de profil
        if ($request->hasFile('photo_profil')) {
            // Supprimer l'ancienne photo si elle existe
            if ($user->photo_profil && Storage::disk('public')->exists($user->photo_profil)) {
                Storage::disk('public')->delete($user->photo_profil);
            }

            // Sauvegarder la nouvelle photo
            $path = $request->file('photo_profil')->store('photos-profil', 'public');
            $validated['photo_profil'] = $path;
        }

        // Mise à jour des informations
        $user->update($validated);

        return redirect()
            ->route('private.profil.index')
            ->with('success', 'Vos informations personnelles ont été mises à jour avec succès.');
    }

    /**
     * Mettre à jour le mot de passe de l'utilisateur
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
        ], [
            'current_password.required' => 'Le mot de passe actuel est obligatoire.',
            'password.required' => 'Le nouveau mot de passe est obligatoire.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ]);

        // Vérifier que le mot de passe actuel est correct
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'Le mot de passe actuel est incorrect.'
            ])->withInput();
        }

        // Vérifier que le nouveau mot de passe est différent de l'ancien
        if (Hash::check($validated['password'], $user->password)) {
            return back()->withErrors([
                'password' => 'Le nouveau mot de passe doit être différent de l\'ancien.'
            ])->withInput();
        }

        // Mise à jour du mot de passe
        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return redirect()
            ->route('private.profil.index')
            ->with('success', 'Votre mot de passe a été modifié avec succès.');
    }

    /**
     * Afficher le formulaire de modification du mot de passe
     */
    public function editPassword()
    {
        return view('components.private.profil.edit-password');
    }

    /**
     * Supprimer la photo de profil
     */
    public function deletePhoto()
    {
        $user = Auth::user();

        if ($user->photo_profil && Storage::disk('public')->exists($user->photo_profil)) {
            Storage::disk('public')->delete($user->photo_profil);
        }

        $user->update(['photo_profil' => null]);

        return redirect()
            ->route('private.profil.edit')
            ->with('success', 'Votre photo de profil a été supprimée avec succès.');
    }

    /**
     * Afficher les informations spirituelles de l'utilisateur
     */
    public function showSpirituel()
    {
        $user = Auth::user()->load('classe');

        return view('components.private.profil.spirituel', compact('user'));
    }

    /**
     * Mettre à jour les informations spirituelles
     * (uniquement le témoignage, dons spirituels et demandes de prière)
     */
    public function updateSpirituel(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'temoignage' => ['nullable', 'string', 'max:5000'],
            'dons_spirituels' => ['nullable', 'string', 'max:2000'],
            'demandes_priere' => ['nullable', 'string', 'max:2000'],
        ], [
            'temoignage.max' => 'Le témoignage ne doit pas dépasser 5000 caractères.',
            'dons_spirituels.max' => 'Les dons spirituels ne doivent pas dépasser 2000 caractères.',
            'demandes_priere.max' => 'Les demandes de prière ne doivent pas dépasser 2000 caractères.',
        ]);

        $user->update($validated);

        return redirect()
            ->route('private.profil.spirituel')
            ->with('success', 'Vos informations spirituelles ont été mises à jour avec succès.');
    }
}
