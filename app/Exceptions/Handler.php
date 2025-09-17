<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Session\TokenMismatchException;
use App\Models\Error404Log;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Configuration des pages d'erreur personnalisées
     */
    private $errorPages = [
        400 => ['view' => 'errors.400', 'title' => 'Requête Incorrecte', 'color' => 'orange', 'icon' => 'fa-exclamation-triangle'],
        401 => ['view' => 'errors.401', 'title' => 'Non Authentifié', 'color' => 'red', 'icon' => 'fa-user-lock'],
        403 => ['view' => 'errors.403', 'title' => 'Accès Non Autorisé', 'color' => 'red', 'icon' => 'fa-shield-alt'],
        404 => ['view' => 'errors.404', 'title' => 'Page Non Trouvée', 'color' => 'purple', 'icon' => 'fa-search'],
        405 => ['view' => 'errors.405', 'title' => 'Méthode Non Autorisée', 'color' => 'yellow', 'icon' => 'fa-ban'],
        419 => ['view' => 'errors.419', 'title' => 'Session Expirée', 'color' => 'blue', 'icon' => 'fa-clock'],
        422 => ['view' => 'errors.422', 'title' => 'Données Invalides', 'color' => 'orange', 'icon' => 'fa-exclamation-circle'],
        429 => ['view' => 'errors.429', 'title' => 'Trop de Requêtes', 'color' => 'red', 'icon' => 'fa-tachometer-alt'],
        500 => ['view' => 'errors.500', 'title' => 'Erreur Serveur', 'color' => 'red', 'icon' => 'fa-server'],
        502 => ['view' => 'errors.502', 'title' => 'Passerelle Défectueuse', 'color' => 'red', 'icon' => 'fa-plug'],
        503 => ['view' => 'errors.503', 'title' => 'Service Indisponible', 'color' => 'orange', 'icon' => 'fa-wrench'],
        504 => ['view' => 'errors.504', 'title' => 'Timeout de la Passerelle', 'color' => 'yellow', 'icon' => 'fa-hourglass'],
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {

        // Gestion spécifique selon le type d'exception
        if ($e instanceof NotFoundHttpException) {
            return $this->renderCustomError($request, 404, $e);
        }

        if ($e instanceof AccessDeniedHttpException) {
            return $this->renderCustomError($request, 403, $e);
        }

        if ($e instanceof AuthenticationException) {
            return $this->renderCustomError($request, 401, $e);
        }

        if ($e instanceof ValidationException) {
            return $this->renderCustomError($request, 422, $e);
        }

        if ($e instanceof TokenMismatchException) {
            return $this->renderCustomError($request, 419, $e);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return $this->renderCustomError($request, 405, $e);
        }

        if ($e instanceof TooManyRequestsHttpException) {
            return $this->renderCustomError($request, 429, $e);
        }

        if ($e instanceof ModelNotFoundException) {
            return $this->renderCustomError($request, 404, $e);
        }

        // Gestion des erreurs HTTP génériques
        if ($e instanceof HttpException) {
            return $this->renderCustomError($request, $e->getStatusCode(), $e);
        }

        // Erreurs serveur (500, etc.)
        return $this->renderCustomError($request, 500, $e);
    }

    /**
     * Rendu personnalisé des erreurs
     */
    private function renderCustomError(Request $request, int $statusCode, Throwable $exception)
    {
        // Log spécifique selon le type d'erreur
        $this->logError($request, $statusCode, $exception);

        // Réponse JSON pour les APIs
        if ($request->expectsJson()) {
            return $this->renderJsonError($request, $statusCode, $exception);
        }

        // Réponse AJAX
        if ($request->ajax()) {
            return $this->renderAjaxError($request, $statusCode, $exception);
        }

        // Redirection intelligente pour 404
        if ($statusCode === 404) {
            $redirectUrl = $this->getSmartRedirect($request->path());
            if ($redirectUrl) {
                Log::info('Redirection automatique 404', [
                    'from' => $request->fullUrl(),
                    'to' => $redirectUrl,
                    'user_id' => auth()->id()
                ]);
                return redirect($redirectUrl, 301);
            }
        }
dd($exception);
        // Rendu de la page d'erreur personnalisée
        return $this->renderErrorPage($request, $statusCode, $exception);
    }

    /**
     * Rendu de la page d'erreur personnalisée
     */
    private function renderErrorPage(Request $request, int $statusCode, Throwable $exception)
    {
        $errorConfig = $this->errorPages[$statusCode] ?? $this->errorPages[500];

        $data = [
            'statusCode' => $statusCode,
            'title' => $errorConfig['title'],
            'color' => $errorConfig['color'],
            'icon' => $errorConfig['icon'],
            'message' => $this->getErrorMessage($statusCode, $exception),
            'description' => $this->getErrorDescription($statusCode),
            'exception' => $exception,
            'request' => $request,
        ];

        if($statusCode === 401){
            return redirect()->route('security.login');
        }


        // Données spécifiques selon le type d'erreur
        switch ($statusCode) {
            case 404:
                $data = array_merge($data, [
                    'suggested_actions' => $this->getSuggestedActions($request->path()),
                    'similar_pages' => $this->getSimilarPaths($request->path()),
                    'popular_pages' => $this->getPopularPages()
                ]);
                break;

            case 403:
                $data['contact_admin'] = true;
                break;

            case 419:
                $data['refresh_required'] = true;
                break;

            case 429:
                if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface) {
                    $data['retry_after'] = $exception->getHeaders()['Retry-After'] ?? 60;
                } else {
                    $data['retry_after'] = 60;
                }
                break;

            case 500:
                $data['support_info'] = config('app.support_email', 'support@example.com');
                break;
        }

        // Vérifier si la vue existe, sinon utiliser la vue générique
        $viewName = $errorConfig['view'];

        if (!view()->exists($viewName)) {
            $viewName = 'errors.generic';
        }


        return response()->view($viewName, $data, $statusCode);
    }

    /**
     * Réponse JSON pour les erreurs API
     */
    private function renderJsonError(Request $request, int $statusCode, Throwable $exception)
    {
        $errorConfig = $this->errorPages[$statusCode] ?? $this->errorPages[500];

        $response = [
            'error' => true,
            'status_code' => $statusCode,
            'message' => $this->getErrorMessage($statusCode, $exception),
            'title' => $errorConfig['title'],
            'timestamp' => now()->toISOString(),
            'path' => $request->path(),
            'method' => $request->method(),
        ];

        // Informations supplémentaires selon l'erreur
        switch ($statusCode) {
            case 404:
                $response['suggestions'] = $this->getApiSuggestions($request->path());
                break;

            case 422:
                if ($exception instanceof ValidationException) {
                    $response['errors'] = $exception->errors();
                }
                break;

            case 429:
                if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface) {
                    $response['retry_after'] = $exception->getHeaders()['Retry-After'] ?? 60;
                } else {
                    $response['retry_after'] = 60;
                }

                break;
        }

        // En mode debug, ajouter plus d'infos
        if (config('app.debug') && $statusCode >= 500) {
            $response['debug'] = [
                'exception' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString()
            ];
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Réponse AJAX pour les erreurs
     */
    private function renderAjaxError(Request $request, int $statusCode, Throwable $exception)
    {
        return response()->view('components.ajax.error', [
            'statusCode' => $statusCode,
            'message' => $this->getErrorMessage($statusCode, $exception),
            'title' => $this->errorPages[$statusCode]['title'] ?? 'Erreur',
            'icon' => $this->errorPages[$statusCode]['icon'] ?? 'fa-exclamation',
        ], $statusCode);
    }

    /**
     * Messages d'erreur personnalisés
     */
    private function getErrorMessage(int $statusCode, Throwable $exception): string
    {
        $messages = [
            400 => 'La requête envoyée est incorrecte ou malformée.',
            401 => 'Vous devez vous authentifier pour accéder à cette ressource.',
            403 => 'Vous n\'avez pas les permissions nécessaires pour accéder à cette page.',
            404 => 'La page que vous cherchez semble avoir disparu.',
            405 => 'La méthode HTTP utilisée n\'est pas autorisée pour cette ressource.',
            419 => 'Votre session a expiré. Veuillez actualiser la page.',
            422 => 'Les données soumises ne sont pas valides.',
            429 => 'Trop de requêtes ont été effectuées. Veuillez patienter.',
            500 => 'Une erreur interne du serveur s\'est produite.',
            502 => 'Le serveur a reçu une réponse invalide.',
            503 => 'Le service est temporairement indisponible.',
            504 => 'Le serveur a mis trop de temps à répondre.',
        ];

        return $messages[$statusCode] ?? 'Une erreur inattendue s\'est produite.';
    }

    /**
     * Descriptions détaillées des erreurs
     */
    private function getErrorDescription(int $statusCode): string
    {
        $descriptions = [
            400 => 'La requête ne peut pas être traitée car elle contient des erreurs de syntaxe.',
            401 => 'Connectez-vous pour accéder à cette fonctionnalité.',
            403 => 'Contactez votre administrateur si vous pensez que c\'est une erreur.',
            404 => 'Il est possible que la page ait été déplacée, supprimée ou que l\'adresse soit incorrecte.',
            405 => 'Vérifiez que vous utilisez la bonne méthode (GET, POST, etc.).',
            419 => 'Pour des raisons de sécurité, votre session a expiré.',
            422 => 'Vérifiez les informations saisies et corrigez les erreurs.',
            429 => 'Veuillez patienter avant de faire une nouvelle tentative.',
            500 => 'Nos équipes techniques ont été informées de ce problème.',
            502 => 'Un problème de communication entre nos serveurs s\'est produit.',
            503 => 'Maintenance en cours ou surcharge temporaire.',
            504 => 'Le temps d\'attente maximal a été dépassé.',
        ];

        return $descriptions[$statusCode] ?? 'Veuillez réessayer plus tard.';
    }

    /**
     * Log les erreurs avec contexte
     */
    private function logError(Request $request, int $statusCode, Throwable $exception): void
    {
        $context = [
            'status_code' => $statusCode,
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => auth()->id(),
            'session_id' => session()->getId(),
            'referrer' => $request->headers->get('referer'),
            'timestamp' => now(),
        ];

        // Log spécifique selon le type d'erreur
        switch ($statusCode) {
            case 404:
                Log::channel('404')->info('Page 404', $context);
                $this->updateCacheCounters($request);
                break;

            case 403:
                Log::channel('security')->warning('Accès refusé', $context);
                break;

            case 500:
                Log::channel('errors')->error('Erreur serveur', array_merge($context, [
                    'exception' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ]));
                break;

            default:
                Log::info("Erreur HTTP {$statusCode}", $context);
        }
    }

    // ... [Garder toutes les méthodes utilitaires existantes] ...

    /**
     * Redirection intelligente basée sur des patterns courants
     */
    private function getSmartRedirect(string $path): ?string
    {
        $redirects = [
            'admin/classes' => 'private/classes',
            'admin/dashboard' => 'private/dashboard',
            'admin' => 'private/dashboard',
            'dashboard' => 'private/dashboard',
            'login' => 'security/login',
            'logout' => 'security/logout',
            'classe' => 'private/classes',
            'dashbord' => 'private/dashboard',
            'dashborad' => 'private/dashboard',
            'statistique' => 'private/classes/statistiques',
            'stats' => 'private/classes/statistiques',
            'classes.html' => 'private/classes',
            'dashboard.html' => 'private/dashboard',
            'index.php' => '/',
            'home.php' => '/',
            'user' => 'private/users',
            'profile' => 'private/profile',
            'setting' => 'private/settings',
            'settings' => 'private/settings'
        ];

        $cleanPath = trim($path, '/');

        if (isset($redirects[$cleanPath])) {
            try {
                return route($redirects[$cleanPath]) ?? url($redirects[$cleanPath]);
            } catch (\Exception $e) {
                return url($redirects[$cleanPath]);
            }
        }

        return $this->findSimilarExistingRoute($cleanPath);
    }

    /**
     * Trouve une route existante similaire
     */
    private function findSimilarExistingRoute(string $path): ?string
    {
        try {
            $routes = Route::getRoutes();
            $bestMatch = null;
            $bestScore = 0;

            foreach ($routes as $route) {
                $routeUri = $route->uri();

                if (strpos($routeUri, '{') !== false ||
                    strpos($routeUri, 'api/') === 0 ||
                    strpos($routeUri, '_') === 0) {
                    continue;
                }

                similar_text($path, $routeUri, $percent);

                if ($percent > $bestScore && $percent >= 70) {
                    $bestScore = $percent;
                    $bestMatch = url($routeUri);
                }
            }

            return $bestMatch;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Suggestions d'actions basées sur le chemin
     */
    private function getSuggestedActions(string $path): array
    {
        // ... [Garder la logique existante] ...
        return [];
    }

    /**
     * Met à jour les compteurs dans le cache
     */
    private function updateCacheCounters(Request $request): void
    {
        try {
            $dailyKey = '404_count_' . now()->format('Y-m-d');
            Cache::increment($dailyKey, 1);
            Cache::put($dailyKey, Cache::get($dailyKey, 0), now()->endOfDay());

            $pathKey = '404_path_' . md5($request->path());
            Cache::increment($pathKey, 1);
            Cache::put($pathKey, Cache::get($pathKey, 0), now()->addDays(7));
        } catch (\Exception $e) {
            Log::error('Erreur mise à jour cache 404', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Obtient des chemins similaires
     */
    private function getSimilarPaths(string $path): array
    {
        // ... [Garder la logique existante] ...
        return [];
    }

    /**
     * Obtient des suggestions pour l'API
     */
    private function getApiSuggestions(string $path): array
    {
        return $this->getSimilarPaths($path);
    }

    /**
     * Obtient les pages populaires
     */
    private function getPopularPages(): array
    {
        return [
            [
                'name' => 'Tableau de bord',
                'url' => route('private.dashboard'),
                'icon' => 'fas fa-tachometer-alt'
            ],
            [
                'name' => 'Classes',
                'url' => route('private.classes.index'),
                'icon' => 'fas fa-chalkboard-teacher'
            ]
        ];
    }
}
