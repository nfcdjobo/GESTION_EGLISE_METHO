<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Ajustez selon votre logique d'autorisation
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Informations personnelles
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'date_naissance' => 'nullable|date|before:today',
            'sexe' => 'required|in:masculin,feminin',

            // Contact
            'telephone_1' => 'required|string|max:20',
            'telephone_2' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email',

            // Adresse
            'adresse_ligne_1' => 'required|string|max:255',
            'adresse_ligne_2' => 'nullable|string|max:255',
            'ville' => 'required|string|max:100',
            'code_postal' => 'nullable|string|max:10',
            'region' => 'nullable|string|max:100',
            'pays' => 'nullable|string|max:2',

            // Informations familiales
            'statut_matrimonial' => 'nullable|in:celibataire,marie,divorce,veuf',
            'nombre_enfants' => 'nullable|integer|min:0',

            // Informations professionnelles
            'profession' => 'nullable|string|max:100',
            'employeur' => 'nullable|string|max:100',

            // Informations d'église
            'classe_id' => 'nullable|exists:classes,id',
            'date_adhesion' => 'nullable|date',
            'statut_membre' => 'required|in:actif,inactif,visiteur,nouveau_converti',
            'statut_bapteme' => 'required|in:non_baptise,baptise,confirme',
            'date_bapteme' => 'nullable|date|required_if:statut_bapteme,baptise,confirme',
            'eglise_precedente' => 'nullable|string|max:255',

            // Contact d'urgence
            'contact_urgence_nom' => 'nullable|string|max:255',
            'contact_urgence_telephone' => 'nullable|string|max:20',
            'contact_urgence_relation' => 'nullable|string|max:100',

            // Compte
            'password' => [
                'required',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
            ],
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',

            // Photo
            'photo_profil' => 'nullable|image|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'prenom.required' => 'Le prénom est obligatoire.',
            'nom.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'telephone_1.required' => 'Le numéro de téléphone principal est obligatoire.',
            'sexe.required' => 'Le sexe est obligatoire.',
            'sexe.in' => 'Le sexe doit être masculin ou féminin.',
            'date_naissance.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'statut_membre.required' => 'Le statut de membre est obligatoire.',
            'statut_bapteme.required' => 'Le statut de baptême est obligatoire.',
            'date_bapteme.required_if' => 'La date de baptême est obligatoire pour les membres baptisés.',
            'photo_profil.image' => 'Le fichier doit être une image.',
            'photo_profil.max' => 'L\'image ne doit pas dépasser 2 Mo.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'prenom' => 'prénom',
            'nom' => 'nom',
            'date_naissance' => 'date de naissance',
            'telephone_1' => 'téléphone principal',
            'telephone_2' => 'téléphone secondaire',
            'adresse_ligne_1' => 'adresse',
            'code_postal' => 'code postal',
            'statut_matrimonial' => 'statut matrimonial',
            'nombre_enfants' => 'nombre d\'enfants',
            'classe_id' => 'classe',
            'date_adhesion' => 'date d\'adhésion',
            'statut_membre' => 'statut de membre',
            'statut_bapteme' => 'statut de baptême',
            'date_bapteme' => 'date de baptême',
            'eglise_precedente' => 'église précédente',
            'contact_urgence_nom' => 'nom du contact d\'urgence',
            'contact_urgence_telephone' => 'téléphone du contact d\'urgence',
            'contact_urgence_relation' => 'relation avec le contact d\'urgence',
            'photo_profil' => 'photo de profil',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Nettoyer les données avant validation si nécessaire
        if ($this->has('telephone_1')) {
            $this->merge([
                'telephone_1' => $this->cleanPhoneNumber($this->telephone_1),
            ]);
        }

        if ($this->has('telephone_2')) {
            $this->merge([
                'telephone_2' => $this->cleanPhoneNumber($this->telephone_2),
            ]);
        }
    }

    /**
     * Nettoyer un numéro de téléphone
     */
    private function cleanPhoneNumber(?string $phone): ?string
    {
        if (!$phone) {
            return null;
        }

        // Supprimer tous les caractères non numériques sauf le +
        return preg_replace('/[^\d+]/', '', $phone);
    }
}
