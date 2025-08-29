<?php

// =================================================================
// app/Http/Resources/SubscriptionResource.php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'montant_souscrit' => $this->montant_souscrit,
            'montant_paye' => $this->montant_paye,
            'reste_a_payer' => $this->reste_a_payer,
            'statut' => $this->statut,
            'date_souscription' => $this->date_souscription->format('Y-m-d'),
            'date_echeance' => $this->date_echeance?->format('Y-m-d'),

            // États calculés
            'est_soldee' => $this->est_soldee,
            'est_en_retard' => $this->est_en_retard,
            'pourcentage_paye' => $this->pourcentage_paye,
            'prochain_montant_minimum' => $this->prochain_montant_minimum,

            // Relations conditionnelles
            'souscripteur' => UserResource::make($this->whenLoaded('souscripteur')),
            'fimeco' => FimecoResource::make($this->whenLoaded('fimeco')),
            'payments' => SubscriptionPaymentResource::collection($this->whenLoaded('payments')),
            'payments_valides' => SubscriptionPaymentResource::collection($this->whenLoaded('paymentsValides')),

            'version' => $this->version,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}

