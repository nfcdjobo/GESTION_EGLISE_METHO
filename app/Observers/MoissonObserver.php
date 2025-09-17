<?php

namespace App\Observers;

use App\Models\Moisson;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MoissonObserver
{
    /**
     * Handle the Moisson "created" event.
     */
    public function created(Moisson $moisson): void
    {
        Log::info('Nouvelle moisson créée', [
            'moisson_id' => $moisson->id,
            'theme' => $moisson->theme,
            'cible' => $moisson->cible,
            'createur' => $moisson->creer_par
        ]);

        // Invalider le cache des statistiques
        $this->invalidateStatsCache();
    }

    /**
     * Handle the Moisson "updated" event.
     */
    public function updated(Moisson $moisson): void
    {
        // Log des modifications importantes
        if ($moisson->wasChanged(['cible', 'status', 'montant_solde'])) {
            Log::info('Moisson modifiée', [
                'moisson_id' => $moisson->id,
                'theme' => $moisson->theme,
                'changes' => $moisson->getChanges(),
                'old_values' => $moisson->getOriginal()
            ]);
        }

        // Invalider le cache si les montants ont changé
        if ($moisson->wasChanged(['montant_solde', 'cible', 'status'])) {
            $this->invalidateStatsCache();
        }

        // Notification si objectif atteint
        if ($moisson->wasChanged('montant_solde') &&
            $moisson->montant_solde >= $moisson->cible &&
            $moisson->getOriginal('montant_solde') < $moisson->cible) {

            $this->notifierObjectifAtteint($moisson);
        }
    }

    /**
     * Handle the Moisson "deleted" event.
     */
    public function deleted(Moisson $moisson): void
    {
        Log::warning('Moisson supprimée', [
            'moisson_id' => $moisson->id,
            'theme' => $moisson->theme,
            'montant_collecte' => $moisson->montant_solde
        ]);

        $this->invalidateStatsCache();
    }

    /**
     * Handle the Moisson "restored" event.
     */
    public function restored(Moisson $moisson): void
    {
        Log::info('Moisson restaurée', [
            'moisson_id' => $moisson->id,
            'theme' => $moisson->theme
        ]);

        $this->invalidateStatsCache();
    }

    /**
     * Handle the Moisson "force deleted" event.
     */
    public function forceDeleted(Moisson $moisson): void
    {
        Log::critical('Moisson définitivement supprimée', [
            'moisson_id' => $moisson->id,
            'theme' => $moisson->theme
        ]);

        $this->invalidateStatsCache();
    }

    /**
     * Invalide le cache des statistiques
     */
    private function invalidateStatsCache(): void
    {
        Cache::tags(['moisson_stats'])->flush();

        // Clés spécifiques à invalider
        $keysToInvalidate = [
            'moisson_stats_global',
            'moisson_dashboard_data',
            'moisson_performance_data'
        ];

        foreach ($keysToInvalidate as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Notifie l'atteinte de l'objectif
     */
    private function notifierObjectifAtteint(Moisson $moisson): void
    {
        // Ici vous pouvez implémenter votre système de notification
        // Par exemple : envoyer un email, notification push, etc.

        Log::info('🎉 Objectif de moisson atteint !', [
            'moisson_id' => $moisson->id,
            'theme' => $moisson->theme,
            'objectif' => $moisson->cible,
            'collecte' => $moisson->montant_solde,
            'supplement' => $moisson->montant_supplementaire
        ]);

        // Exemple d'événement à dispatcher
        // event(new ObjectifMoissonAtteint($moisson));
    }
}
