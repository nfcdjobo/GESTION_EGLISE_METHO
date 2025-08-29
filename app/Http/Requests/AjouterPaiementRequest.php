<?php

// =================================================================
// app/Http/Requests/AjouterPaiementRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AjouterPaiementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subscription_id' => ['required', 'uuid', 'exists:subscriptions,id'],
            'montant' => [
                'required',
                'numeric',
                'min:' . config('fimeco.montant_minimum_paiement', 5),
                'max:999999.99'
            ],
            'type_paiement' => [
                'required',
                Rule::in(array_keys(config('fimeco.types_paiement_autorises')))
            ],
            'reference_paiement' => ['nullable', 'string', 'max:100'],
            'date_paiement' => ['nullable', 'date', 'before_or_equal:today'],
            'commentaire' => ['nullable', 'string', 'max:500'],
            'expected_version' => ['nullable', 'integer', 'min:0']
        ];
    }

    public function messages(): array
    {
        return [
            'subscription_id.required' => 'La souscription doit être spécifiée.',
            'subscription_id.exists' => 'Cette souscription n\'existe pas.',
            'montant.required' => 'Le montant est requis.',
            'montant.min' => 'Le montant minimum est de :min.',
            'type_paiement.required' => 'Le type de paiement est requis.',
            'type_paiement.in' => 'Type de paiement non autorisé.',
            'date_paiement.before_or_equal' => 'La date de paiement ne peut pas être dans le futur.'
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->subscription_id && $this->montant) {
                $subscription = \App\Models\Subscription::find($this->subscription_id);

                if ($subscription) {
                    // Vérifier que le montant ne dépasse pas le reste à payer
                    if ($this->montant > $subscription->reste_a_payer) {
                        $validator->errors()->add('montant',
                            'Le montant ne peut pas dépasser le reste à payer (' . $subscription->reste_a_payer . ').'
                        );
                    }

                    // Vérifier que la souscription peut recevoir des paiements
                    if (!$subscription->peutRecevoirPaiement($this->montant)) {
                        $validator->errors()->add('subscription_id',
                            'Cette souscription ne peut plus recevoir de paiements.'
                        );
                    }
                }
            }
        });
    }
}
