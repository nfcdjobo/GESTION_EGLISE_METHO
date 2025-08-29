<?php

// =================================================================
// app/Console/Commands/FimecoRappelsPaiements.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\RappelPaiementFimeco;
use Carbon\Carbon;

class FimecoRappelsPaiements extends Command
{
    protected $signature = 'fimeco:rappels-paiements';

    protected $description = 'Envoyer des rappels de paiement pour les souscriptions FIMECO';

    public function handle(): int
    {
        $joursRappel = config('fimeco.notifications.rappel_paiement_jours', [7, 3, 1]);
        $compteur = 0;

        foreach ($joursRappel as $jours) {
            $dateEcheance = Carbon::today()->addDays($jours);

            $subscriptions = Subscription::whereNotIn('statut', ['completement_payee', 'annulee'])
                ->where('date_echeance', $dateEcheance)
                ->with(['souscripteur', 'fimeco'])
                ->get();

            foreach ($subscriptions as $subscription) {
                try {
                    $subscription->souscripteur->notify(
                        new RappelPaiementFimeco($subscription, $jours)
                    );
                    $compteur++;
                } catch (\Exception $e) {
                    $this->error("Erreur envoi rappel pour souscription {$subscription->id}: " . $e->getMessage());
                }
            }
        }

        $this->info("Rappels envoyés: {$compteur}");
        return Command::SUCCESS;
    }
}
