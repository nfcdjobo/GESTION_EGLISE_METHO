<?php

namespace App\Http\Requests;

// =================================================================

namespace App\Http\Requests;

use App\Models\Fimeco;
use Illuminate\Foundation\Http\FormRequest;

class CreerFimecoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Fimeco::class);
    }

    public function rules(): array
    {
        return [
            // 'responsable_id' => ['required', 'uuid', 'exists:users,id'],
            'nom' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'debut' => ['required', 'date', 'before:fin'],
            'fin'   => ['required', 'date', 'after:debut']
        ];
    }

    public function messages(): array
    {
        return [
            // 'responsable_id.required' => 'Le responsable doit être sélectionné.',
            // 'responsable_id.exists' => 'Le responsable sélectionné n\'existe pas.',
            'nom.required' => 'Le nom de la FIMECO est requis.',
            'nom.max' => 'Le nom ne peut pas dépasser 100 caractères.',
            'debut.required' => 'La date de début est requise.',
            'debut.before' => 'La date de début ne peut pas être dans le passé.',
            'fin.required' => 'La date de fin est requise.',
            'fin.after' => 'La date de fin doit être postérieure à la date de début.'
        ];
    }
}
