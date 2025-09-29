<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Observers\SubscriptionObserver;
use Illuminate\Support\ServiceProvider;
use App\Observers\SubscriptionPaymentObserver;

use Illuminate\Support\Facades\View;
use App\Models\Event;
use App\Models\Parametres;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Partager les variables globalement avec tous les vues
        View::composer('*', function ($view) {
            // Récupérer les événements publics
            $AppEvents = Event::where('ouvert_public', true) // Correction: true pour publier
                ->whereNotIn('statut', ["brouillon", "annule", "archive"])
                ->orderBy('date_debut', 'asc')
                ->limit(6)
                ->get();

            // Récupérer les paramètres de l'application
            $AppParametres = Parametres::getInstance();

            // Partager avec toutes les vues
            $view->with([
                'AppEvents' => $AppEvents,
                'AppParametres' => $AppParametres
            ]);
        });

        Carbon::setLocale('fr');
        Subscription::observe(SubscriptionObserver::class);
        SubscriptionPayment::observe(SubscriptionPaymentObserver::class);
    }
}



