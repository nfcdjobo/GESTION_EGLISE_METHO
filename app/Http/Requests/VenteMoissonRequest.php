<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VenteMoissonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'moisson_id' => ['required', 'uuid', 'exists:moissons,id'],
            'categorie' => ['required', Rule::in(array_keys(\App\Models\VenteMoisson::CATEGORIES))],
            'cible' => ['required', 'numeric', 'min:1', 'max:99999999999999.99'],
            'collecter_par' => ['required', 'uuid', 'exists:users,id'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'moisson_id.required' => 'La moisson est obligatoire.',
            'categorie.required' => 'La catégorie de vente est obligatoire.',
            'categorie.in' => 'La catégorie sélectionnée n\'est pas valide.',
            'cible.required' => 'L\'objectif est obligatoire.',
            'cible.min' => 'L\'objectif doit être supérieur à 0.',
            'collecter_par.required' => 'Le collecteur est obligatoire.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
        ];
    }
}
