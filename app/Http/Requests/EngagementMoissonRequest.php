<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class EngagementMoissonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'moisson_id' => ['required', 'uuid', 'exists:moissons,id'],
            'categorie' => ['required', Rule::in(['entite_morale', 'entite_physique'])],
            'donateur_id' => [
                'nullable',
                'uuid',
                Rule::requiredIf($this->categorie === 'entite_physique'),
                'exists:users,id'
            ],
            'nom_entite' => [
                'nullable',
                'string',
                'max:255',
                Rule::requiredIf($this->categorie === 'entite_morale')
            ],
            'cible' => ['required', 'numeric', 'min:1', 'max:99999999999999.99'],
            'collecter_par' => ['required', 'uuid', 'exists:users,id'],
            'telephone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'adresse' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string', 'max:1000'],
            'date_echeance' => ['nullable', 'date', 'after:today'],
            'date_rappel' => ['nullable', 'date', 'after:today', 'before:date_echeance'],
        ];
    }

    public function messages(): array
    {
        return [
            'moisson_id.required' => 'La moisson est obligatoire.',
            'categorie.required' => 'Le type d\'engagement est obligatoire.',
            'categorie.in' => 'Le type d\'engagement sélectionné n\'est pas valide.',
            'donateur_id.required_if' => 'Le donateur est obligatoire pour une entité physique.',
            'nom_entite.required_if' => 'Le nom de l\'entité est obligatoire pour une entité morale.',
            'nom_entite.max' => 'Le nom de l\'entité ne peut pas dépasser 255 caractères.',
            'cible.required' => 'Le montant de l\'engagement est obligatoire.',
            'cible.min' => 'Le montant doit être supérieur à 0.',
            'collecter_par.required' => 'Le responsable de collecte est obligatoire.',
            'telephone.max' => 'Le téléphone ne peut pas dépasser 20 caractères.',
            'email.email' => 'L\'adresse email n\'est pas valide.',
            'adresse.max' => 'L\'adresse ne peut pas dépasser 500 caractères.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
            'date_echeance.after' => 'La date d\'échéance doit être dans le futur.',
            'date_rappel.after' => 'La date de rappel doit être dans le futur.',
            'date_rappel.before' => 'La date de rappel doit être antérieure à l\'échéance.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Nettoyer selon le type d'entité
        if ($this->categorie === 'entite_morale') {
            $this->merge(['donateur_id' => null]);
        } else {
            $this->merge(['nom_entite' => null]);
        }
    }
}
