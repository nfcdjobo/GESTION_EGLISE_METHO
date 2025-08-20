<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckResourcePermission
{
    /**
     * Handle an incoming request.
     * Usage: 'resource.permission:users,update' ou 'resource.permission:posts,delete'
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $resourceAction): Response
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

        // Séparer la ressource et l'action
        [$resource, $action] = explode(',', $resourceAction);

        // Vérifier la permission sur la ressource
        if (!$user->hasResourcePermission($resource, $action)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Vous n\'avez pas la permission d\'effectuer cette action.',
                    'resource' => $resource,
                    'action' => $action
                ], 403);
            }

            abort(403, 'Accès non autorisé.');
        }

        return $next($request);
    }
}
