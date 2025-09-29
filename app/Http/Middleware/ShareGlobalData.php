<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\Event;
use App\Models\Parametres;

class ShareGlobalData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Partager uniquement pour les routes publiques
        if ($request->is('*') && !$request->is('admin/*') && !$request->is('private/*')) {
            // Récupérer les événements publics
            $AppEvents = Event::where('publier', true)
                ->whereNotIn('statut', ["brouillon", "annule", "archive"])
                ->orderBy('date_debut', 'asc')
                ->limit(6)
                ->get();

            // Récupérer les paramètres de l'application
            $AppParametres = Parametres::getInstance();

            // Partager avec les vues
            View::share([
                'AppEvents' => $AppEvents,
                'AppParametres' => $AppParametres
            ]);
        }

        return $next($request);
    }
}

// N'oubliez pas d'enregistrer ce middleware dans app/Http/Kernel.php :
// Dans $middleware ou $middlewareGroups['web']
