<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CKEditorPerformanceMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);

        $response = $next($request);

        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000; // en millisecondes

        // Log des performances si activé
        if (config('ckeditor.monitoring.enabled') &&
            $executionTime > config('ckeditor.monitoring.performance_threshold')) {

            Log::warning('CKEditor slow response detected', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'execution_time' => $executionTime,
                'memory_usage' => memory_get_usage(true),
                'user_id' => auth()->id(),
            ]);
        }

        // Ajouter des headers de performance en développement
        if (app()->environment('local', 'staging')) {
            $response->headers->set('X-CKEditor-Execution-Time', $executionTime);
            $response->headers->set('X-CKEditor-Memory-Usage', memory_get_usage(true));
        }

        return $response;
    }
}
