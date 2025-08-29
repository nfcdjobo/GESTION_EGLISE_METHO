<?php

// =================================================================
// app/Jobs/MettreAJourStatistiques.php

namespace App\Jobs;

use App\Models\Fimeco;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class MettreAJourStatistiques implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private ?string $fimecoId = null)
    {
        $this->queue = 'default';
    }

    public function handle(): void
    {
        if ($this->fimecoId) {
            $this->mettreAJourStatistiquesFimeco($this->fimecoId);
        } else {
            $this->mettreAJourToutesStatistiques();
        }
    }

    private function mettreAJourStatistiquesFimeco(string $fimecoId): void
    {
        $fimeco = Fimeco::with(['subscriptions.paymentsValides'])->find($fimecoId);

        if (!$fimeco) {
            return;
        }

        $statistiques = $fimeco->calculerStatistiques();

        // Mise en cache des statistiques pour 1 heure
        Cache::put("fimeco_stats_{$fimecoId}", $statistiques, 3600);
    }

    private function mettreAJourToutesStatistiques(): void
    {
        $fimecos = Fimeco::with(['subscriptions.paymentsValides'])->get();

        foreach ($fimecos as $fimeco) {
            $this->mettreAJourStatistiquesFimeco($fimeco->id);
        }

        // Statistiques globales
        $statistiquesGlobales = [
            'total_fimecos_actives' => Fimeco::active()->count(),
            'total_souscriptions' => \App\Models\Subscription::count(),
            'total_montant_souscrit' => \App\Models\Subscription::sum('montant_souscrit'),
            'total_montant_paye' => \App\Models\Subscription::sum('montant_paye'),
            'paiements_en_attente' => \App\Models\SubscriptionPayment::enAttente()->count()
        ];

        Cache::put('fimeco_stats_globales', $statistiquesGlobales, 3600);
    }
}
