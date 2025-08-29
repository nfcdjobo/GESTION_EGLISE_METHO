<?php

namespace App\Jobs;

use App\Models\Subscription;
use App\Notifications\RappelPaiementFimeco;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EnvoyerRappelsPaiements implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private int $joursRappel)
    {
        $this->queue = 'notifications';
    }

    public function uniqueId(): string
    {
        return "rappels_paiements_{$this->joursRappel}";
    }

    public function handle(): void
    {
        $dateEcheance = Carbon::today()->addDays($this->joursRappel);

        $subscriptions = Subscription::whereNotIn('statut', ['completement_payee', 'annulee'])
            ->where('date_echeance', $dateEcheance)
            ->with(['souscripteur', 'fimeco'])
            ->get();

        $compteur = 0;
        $erreurs = 0;

        foreach ($subscriptions as $subscription) {
            try {
                $subscription->souscripteur->notify(
                    new RappelPaiementFimeco($subscription, $this->joursRappel)
                );
                $compteur++;
            } catch (\Exception $e) {
                $erreurs++;
                Log::error("Erreur envoi rappel paiement", [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        Log::info("Rappels de paiement envoyés", [
            'jours_rappel' => $this->joursRappel,
            'succes' => $compteur,
            'erreurs' => $erreurs
        ]);
    }
}
