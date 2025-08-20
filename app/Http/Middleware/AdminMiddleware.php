<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!auth()->check()) {
            return redirect()->route('security.login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        /**
         * @var User $user
         */
        $user = auth()->user();

        // Vérifier si l'utilisateur a le rôle d'administrateur
        if (!$user->hasRole('admin') && !$user->hasRole('super_admin')) {
            abort(403, 'Accès refusé. Vous n\'avez pas les permissions nécessaires.');
        }

        // Vérifier si le compte est actif
        if (!$user->actif) {
            auth()->logout();
            return redirect()->route('security.login')->with('error', 'Votre compte a été désactivé.');
        }

        return $next($request);
    }
}
