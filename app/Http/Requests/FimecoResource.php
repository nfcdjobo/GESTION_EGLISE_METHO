<?php

// =================================================================
// app/Http/Resources/FimecoResource.php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FimecoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'description' => $this->description,
            'debut' => $this->debut->format('Y-m-d'),
            'fin' => $this->fin->format('Y-m-d'),
            'cible' => $this->cible,
            'statut' => $this->statut,
            'est_en_cours' => $this->est_en_cours,
            'est_terminee' => $this->est_terminee,

            // Statistiques
            'total_souscriptions' => $this->total_souscriptions,
            'total_paye' => $this->total_paye,
            'pourcentage_realisation' => $this->pourcentage_realisation,
            'nombre_souscripteurs' => $this->nombre_membres_souscripteurs,

            // Relations conditionnelles
            'responsable' => UserResource::make($this->whenLoaded('responsable')),
            'subscriptions' => SubscriptionResource::collection($this->whenLoaded('subscriptions')),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}

