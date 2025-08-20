<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Non authentifié.'
                ], 401);
            }
            return redirect()->route('security.login');
        }

        /** @var User|null $user */
        $user = auth()->user();

        // Vérifier si l'utilisateur a au moins un des rôles
        if (count($roles) > 0) {
            if (!$user->hasAnyRole($roles)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Vous n\'avez pas le rôle nécessaire pour accéder à cette ressource.',
                        'required_roles' => $roles
                    ], 403);
                }

                abort(403, 'Accès non autorisé.');
            }
        }

        return $next($request);
    }
}

