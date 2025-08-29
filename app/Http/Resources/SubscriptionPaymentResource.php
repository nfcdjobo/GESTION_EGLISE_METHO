<?php

// =================================================================
// app/Http/Resources/SubscriptionPaymentResource.php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionPaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'montant' => $this->montant,
            'ancien_reste' => $this->ancien_reste,
            'nouveau_reste' => $this->nouveau_reste,
            'type_paiement' => $this->type_paiement,
            'type_paiement_libelle' => config('fimeco.types_paiement_autorises')[$this->type_paiement] ?? $this->type_paiement,
            'reference_paiement' => $this->reference_paiement,
            'statut' => $this->statut,
            'date_paiement' => $this->date_paiement->format('Y-m-d H:i:s'),
            'date_validation' => $this->date_validation?->format('Y-m-d H:i:s'),
            'commentaire' => $this->commentaire,

            // États calculés
            'est_valide' => $this->est_valide,
            'est_en_attente' => $this->est_en_attente,
            'peut_etre_valide' => $this->peut_etre_valide,
            'peut_etre_annule' => $this->peut_etre_annule,

            // Relations conditionnelles
            'subscription' => SubscriptionResource::make($this->whenLoaded('subscription')),
            'validateur' => UserResource::make($this->whenLoaded('validateur')),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}

