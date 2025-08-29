<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AnnonceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // L'autorisation est gérée par les policies
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'titre' => 'required|string|max:200',
            'contenu' => 'required|string|min:10',
            'type_annonce' => [
                'required',
                Rule::in(['evenement', 'administrative', 'pastorale', 'urgence', 'information'])
            ],
            'niveau_priorite' => [
                'nullable',
                Rule::in(['normal', 'important', 'urgent'])
            ],
            'audience_cible' => [
                'nullable',
                Rule::in(['tous', 'membres', 'leadership', 'jeunes'])
            ],
            'contact_principal_id' => 'nullable|exists:users,id',
            'lieu_evenement' => 'nullable|string|max:255',
            'afficher_site_web' => 'boolean',
            'annoncer_culte' => 'boolean',
            'date_evenement' => 'nullable|date',
            'expire_le' => 'nullable|date|after:today',
        ];

        // Validation spécifique selon le type d'annonce
        if ($this->type_annonce === 'evenement') {
            $rules['date_evenement'] = 'required|date|after_or_equal:today';
            $rules['lieu_evenement'] = 'required|string|max:255';
        }

        if ($this->type_annonce === 'urgence') {
            $rules['niveau_priorite'] = 'required|in:urgent';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'titre.required' => 'Le titre de l\'annonce est obligatoire.',
            'titre.max' => 'Le titre ne peut pas dépasser 200 caractères.',
            'contenu.required' => 'Le contenu de l\'annonce est obligatoire.',
            'contenu.min' => 'Le contenu doit contenir au moins 10 caractères.',
            'type_annonce.required' => 'Le type d\'annonce est obligatoire.',
            'type_annonce.in' => 'Le type d\'annonce sélectionné n\'est pas valide.',
            'contact_principal_id.exists' => 'Le contact principal sélectionné n\'existe pas.',
            'date_evenement.required' => 'La date de l\'événement est obligatoire pour ce type d\'annonce.',
            'date_evenement.after_or_equal' => 'La date de l\'événement ne peut pas être dans le passé.',
            'lieu_evenement.required' => 'Le lieu de l\'événement est obligatoire pour ce type d\'annonce.',
            'expire_le.after' => 'La date d\'expiration doit être ultérieure à aujourd\'hui.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'titre' => 'titre',
            'contenu' => 'contenu',
            'type_annonce' => 'type d\'annonce',
            'niveau_priorite' => 'niveau de priorité',
            'audience_cible' => 'audience cible',
            'contact_principal_id' => 'contact principal',
            'lieu_evenement' => 'lieu de l\'événement',
            'date_evenement' => 'date de l\'événement',
            'expire_le' => 'date d\'expiration',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Nettoyage automatique du titre
        if ($this->has('titre')) {
            $this->merge([
                'titre' => trim($this->titre),
            ]);
        }

        // Définition des valeurs par défaut
        $this->merge([
            'afficher_site_web' => $this->boolean('afficher_site_web', true),
            'annoncer_culte' => $this->boolean('annoncer_culte', false),
            'niveau_priorite' => $this->niveau_priorite ?? 'normal',
            'audience_cible' => $this->audience_cible ?? 'tous',
        ]);
    }
}
