<?php

// =================================================================
// app/Http/Requests/CreerSouscriptionRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Fimeco;

class CreerSouscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Gérer l'autorisation selon votre logique
    }

    public function rules(): array
    {

        return [
            'souscripteur_id' => ['required', 'exists:users,id'],
            'fimeco_id' => ['required', 'uuid', 'exists:fimecos,id'],
            'montant_souscrit' => [
                'required',
                'numeric',
                'min:' . config('fimeco.montant_minimum_souscription', 10),

            ],
            'commentaire' => ['nullable', 'string', 'max:500']
        ];
    }

    public function messages(): array
    {
        return [
            'souscripteur_id' => 'Le souscripteur doit être authentifié.',
            'fimeco_id.required' => 'La FIMECO doit être sélectionnée.',
            'fimeco_id.exists' => 'Cette FIMECO n\'existe pas.',
            'montant_souscrit.required' => 'Le montant de souscription est requis.',
            'montant_souscrit.min' => 'Le montant minimum est de :min.',

            // 'date_echeance.after' => 'La date d\'échéance doit être dans le futur.'
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Vérifier que la FIMECO est active
            if ($this->fimeco_id) {
                $fimeco = Fimeco::find($this->fimeco_id);
                if ($fimeco && !$fimeco->peutEtreSouscrite()) {
                    $validator->errors()->add('fimeco_id', 'Cette FIMECO n\'accepte plus de nouvelles souscriptions.');
                }
            }

            // Vérifier si l'membres n'a pas déjà souscrit
            if ($this->fimeco_id && auth()->check()) {
                $existingSubscription = \App\Models\Subscription::where('fimeco_id', $this->fimeco_id)
                    ->where('souscripteur_id', $this->souscripteur_id)
                    ->exists();

                if ($existingSubscription) {
                    $validator->errors()->add('fimeco_id', 'Vous avez déjà souscrit à cette FIMECO.');
                }
            }
        });
        // dd($validator->errors());
    }
}
