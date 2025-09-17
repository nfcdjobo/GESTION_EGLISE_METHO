<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
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

        // Super admin a toujours accès
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Vérifier si l'membres a au moins une des permissions
        if (count($permissions) > 0) {
            if (!$user->hasAnyPermission($permissions)) {

                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Vous n\'avez pas les permissions nécessaires pour accéder à cette ressource.',
                        'required_permissions' => $permissions
                    ], 403);
                }

                abort(403, 'Accès non autorisé.');
            }
        }

        return $next($request);
    }
}

