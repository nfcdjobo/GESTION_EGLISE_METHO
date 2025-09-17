<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PassageMoissonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'moisson_id' => ['required', 'uuid', 'exists:moissons,id'],
            'categorie' => ['required', Rule::in(array_keys(\App\Models\PassageMoisson::CATEGORIES))],
            'classe_id' => [
                'nullable',
                'uuid',
                Rule::requiredIf($this->categorie === 'passage_classe_communautaire'),
                Rule::exists('classes', 'id')->where('deleted_at', null)
            ],
            'cible' => ['required', 'numeric', 'min:1', 'max:99999999999999.99'],
            'collecter_par' => ['required', 'uuid', 'exists:users,id'],
            // 'ajustement_montant' => ['nullable', 'numeric']
        ];
    }

    public function messages(): array
    {
        return [
            'moisson_id.required' => 'La moisson est obligatoire.',
            'moisson_id.exists' => 'La moisson sélectionnée n\'existe pas.',
            'categorie.required' => 'La catégorie de passage est obligatoire.',
            'categorie.in' => 'La catégorie sélectionnée n\'est pas valide.',
            'classe_id.required_if' => 'Une classe doit être sélectionnée pour un passage de classe communautaire.',
            'classe_id.exists' => 'La classe sélectionnée n\'existe pas.',
            'cible.required' => 'L\'objectif est obligatoire.',
            'cible.min' => 'L\'objectif doit être supérieur à 0.',
            'collecter_par.required' => 'Le collecteur est obligatoire.',
            'collecter_par.exists' => 'Le collecteur sélectionné n\'existe pas.'
        ];
    }

    protected function prepareForValidation(): void
    {
        // Si ce n'est pas un passage de classe communautaire, supprimer classe_id
        if ($this->categorie !== 'passage_classe_communautaire') {
            $this->merge(['classe_id' => null]);
        }
    }
}
