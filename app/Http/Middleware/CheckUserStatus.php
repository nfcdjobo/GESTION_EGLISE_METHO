<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Vérifier que l'membres est actif
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Non authentifié.'
                ], 401);
            }
            return redirect()->route('security.login');
        }

        $user = auth()->user();

        // Vérifier si l'membres est actif
        if (!$user->actif) {
            auth()->logout();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Votre compte a été désactivé. Veuillez contacter l\'administrateur.'
                ], 403);
            }

            return redirect()->route('security.login')
                ->with('error', 'Votre compte a été désactivé. Veuillez contacter l\'administrateur.');
        }

        return $next($request);
    }
}
