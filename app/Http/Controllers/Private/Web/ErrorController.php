<?php

namespace App\Http\Controllers\Private\Web;

use App\Models\Error404Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class ErrorController extends Controller
{
    /**
     * Gère les pages 404 (non trouvées)
     */
    public function notFound(Request $request)
    {

        // Log de l'erreur 404 pour analytics
        Log::info('Page 404 accédée', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => auth()->id(),
            'referrer' => $request->headers->get('referer'),
            'timestamp' => now()
        ]);

        // Sauvegarder en base pour analytics
        $this->saveErrorLog($request);

        // Suggestions de pages similaires basées sur l'URL
        $suggestions = $this->getSimilarPages($request->path());

        // Statistiques pour la page 404
        $stats = [
            'total_404_today' => $this->get404CountToday(),
            'most_searched_terms' => $this->getMostSearchedTerms(),
            'popular_pages' => $this->getPopularPages(),
            'recent_404_count' => $this->getRecent404Count()
        ];

        return response()->view('components.private.not-fund.404', [
            'exception' => new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException(),
            'requested_url' => $request->fullUrl(),
            'path_segments' => explode('/', trim($request->path(), '/')),
            'suggestions' => $suggestions,
            'stats' => $stats,
            'breadcrumbs' => $this->generateBreadcrumbs($request->path()),
            'smart_redirects' => $this->getSmartRedirectSuggestions($request->path())
        ], 404);
    }

    /**
     * Sauvegarde l'erreur 404 en base de données
     */
    private function saveErrorLog(Request $request): void
    {
        try {
            Error404Log::create([
                'url' => $request->fullUrl(),
                'path' => $request->path(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'user_id' => auth()->id(),
                'session_id' => session()->getId(),
                'referrer' => $request->headers->get('referer'),
            ]);

            // Incrémenter le compteur journalier dans le cache
            $cacheKey = '404_count_' . now()->format('Y-m-d');
            Cache::increment($cacheKey, 1);
            Cache::put($cacheKey, Cache::get($cacheKey, 0), now()->endOfDay());

        } catch (\Exception $e) {
            // Si le modèle n'existe pas, sauvegarder directement en base
            try {
                DB::table('error_404_logs')->insert([
                    'id' => (string) \Illuminate\Support\Str::uuid(),
                    'url' => $request->fullUrl(),
                    'path' => $request->path(),
                    'method' => $request->method(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'user_id' => auth()->id(),
                    'session_id' => session()->getId(),
                    'referrer' => $request->headers->get('referer'),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } catch (\Exception $e) {
                // Si la table n'existe pas, ne pas faire d'erreur
                Log::error('Erreur sauvegarde 404 log', ['error' => $e->getMessage()]);
            }
        }
    }

    /**
     * Compte les erreurs 404 d'aujourd'hui
     */
    private function get404CountToday(): int
    {
        return Cache::remember('404_count_today', 3600, function () {
            try {
                return Error404Log::whereDate('created_at', today())->count();
            } catch (\Exception $e) {
                // Fallback vers la table directement
                try {
                    return DB::table('error_404_logs')
                        ->whereDate('created_at', today())
                        ->count();
                } catch (\Exception $e) {
                    return Cache::get('404_count_' . now()->format('Y-m-d'), 0);
                }
            }
        });
    }

    /**
     * Compte les erreurs 404 des 7 derniers jours
     */
    private function getRecent404Count(): int
    {
        return Cache::remember('404_count_week', 1800, function () {
            try {
                return Error404Log::where('created_at', '>=', now()->subDays(7))->count();
            } catch (\Exception $e) {
                try {
                    return DB::table('error_404_logs')
                        ->where('created_at', '>=', now()->subDays(7))
                        ->count();
                } catch (\Exception $e) {
                    return 0;
                }
            }
        });
    }

    /**
     * Trouve des pages similaires basées sur l'URL demandée
     */
    private function getSimilarPages(string $path): array
    {
        $routes = Route::getRoutes();
        $suggestions = [];

        foreach ($routes as $route) {
            $routePath = $route->uri();

            // Ignorer les routes avec paramètres et les routes système
            if (strpos($routePath, '{') !== false ||
                strpos($routePath, 'api/') === 0 ||
                strpos($routePath, '_') === 0) {
                continue;
            }

            // Calculer la similarité entre les chemins
            similar_text($path, $routePath, $percent);

            if ($percent > 30 && $percent < 100) { // Éviter les correspondances exactes
                $suggestions[] = [
                    'path' => $routePath,
                    'name' => $route->getName(),
                    'similarity' => round($percent, 1),
                    'url' => url($routePath),
                    'methods' => $route->methods()
                ];
            }
        }

        // Trier par similarité décroissante et prendre les 5 premiers
        usort($suggestions, fn($a, $b) => $b['similarity'] <=> $a['similarity']);

        return array_slice($suggestions, 0, 5);
    }

    /**
     * Récupère les termes de recherche les plus populaires
     */
    private function getMostSearchedTerms(): array
    {
        return Cache::remember('most_searched_terms', 3600, function () {
            try {
                return DB::table('search_logs')
                    ->select('query', DB::raw('count(*) as count'))
                    ->whereDate('created_at', '>=', now()->subDays(7))
                    ->groupBy('query')
                    ->orderByDesc('count')
                    ->limit(10)
                    ->get()
                    ->toArray();
            } catch (\Exception $e) {
                // Fallback avec des données exemples basées sur votre app
                return [
                    ['query' => 'classes', 'count' => 45],
                    ['query' => 'inscriptions', 'count' => 32],
                    ['query' => 'planning', 'count' => 28],
                    ['query' => 'profil', 'count' => 21],
                    ['query' => 'statistiques', 'count' => 18],
                    ['query' => 'enseignants', 'count' => 15],
                    ['query' => 'élèves', 'count' => 12],
                    ['query' => 'emploi du temps', 'count' => 10]
                ];
            }
        });
    }

    /**
     * Récupère les pages les plus populaires
     */
    private function getPopularPages(): array
    {
        $pages = [
            [
                'name' => 'Tableau de bord',
                'url' => route('private.dashboard'),
                'icon' => 'fas fa-tachometer-alt',
                'description' => 'Vue d\'ensemble du système',
                'category' => 'Administration'
            ],
            [
                'name' => 'Gestion des classes',
                'url' => route('private.classes.index'),
                'icon' => 'fas fa-chalkboard-teacher',
                'description' => 'Administration des cours',
                'category' => 'Académique'
            ],
            [
                'name' => 'Statistiques des classes',
                'url' => route('private.classes.statistiques'),
                'icon' => 'fas fa-chart-bar',
                'description' => 'Analyse des données',
                'category' => 'Rapports'
            ]
        ];

        // Ajouter des pages conditionnelles selon les routes disponibles
        try {
            if (Route::has('private.users.index')) {
                $pages[] = [
                    'name' => 'Gestion des membres',
                    'url' => route('private.users.index'),
                    'icon' => 'fas fa-users',
                    'description' => 'Administration des comptes',
                    'category' => 'Administration'
                ];
            }

            if (Route::has('private.subscriptions.index')) {
                $pages[] = [
                    'name' => 'Inscriptions',
                    'url' => route('private.inscriptions.index'),
                    'icon' => 'fas fa-user-plus',
                    'description' => 'Gestion des souscriptions',
                    'category' => 'Académique'
                ];
            }
        } catch (\Exception $e) {
            // Ignorer les erreurs de routes
        }

        return $pages;
    }

    /**
     * Génère un fil d'Ariane basé sur le chemin
     */
    private function generateBreadcrumbs(string $path): array
    {
        $segments = array_filter(explode('/', trim($path, '/')));
        $breadcrumbs = [['name' => 'Accueil', 'url' => route('private.dashboard')]];

        $currentPath = '';
        foreach ($segments as $segment) {
            $currentPath .= '/' . $segment;
            $breadcrumbs[] = [
                'name' => $this->formatSegmentName($segment),
                'url' => $currentPath,
                'active' => false
            ];
        }

        // Marquer le dernier élément comme actif
        if (count($breadcrumbs) > 1) {
            $breadcrumbs[count($breadcrumbs) - 1]['active'] = true;
        }

        return $breadcrumbs;
    }

    /**
     * Formate le nom d'un segment d'URL pour l'affichage
     */
    private function formatSegmentName(string $segment): string
    {
        // Remplacer les tirets et underscores par des espaces
        $name = str_replace(['-', '_'], ' ', $segment);

        // Capitaliser et traduire les termes courants
        $translations = [
            'private' => 'Privé',
            'public' => 'Public',
            'classes' => 'Classes',
            'dashboard' => 'Tableau de bord',
            'users' => 'Membress',
            'inscriptions' => 'Inscriptions',
            'statistiques' => 'Statistiques',
            'admin' => 'Administration',
            'profile' => 'Profil',
            'settings' => 'Paramètres'
        ];

        $lowerName = strtolower($name);
        if (isset($translations[$lowerName])) {
            return $translations[$lowerName];
        }

        // Capitaliser chaque mot
        return ucwords($name);
    }

    /**
     * Suggère des redirections intelligentes
     */
    private function getSmartRedirectSuggestions(string $path): array
    {
        $suggestions = [];

        // Patterns courants de fautes de frappe
        $patterns = [
            '/classe[s]?/' => 'private/classes',
            '/dashbord/' => 'private/dashboard',
            '/admini?s?tration/' => 'private/dashboard',
            '/login/' => 'security/login',
            '/profil[e]?/' => 'private/profile',
            '/statistique[s]?/' => 'private/classes/statistiques'
        ];

        foreach ($patterns as $pattern => $redirect) {
            if (preg_match($pattern, $path)) {
                $suggestions[] = [
                    'reason' => 'Correction automatique détectée',
                    'original' => $path,
                    'suggested' => $redirect,
                    'url' => url($redirect),
                    'confidence' => 85
                ];
                break;
            }
        }

        // Vérifier les extensions inutiles
        if (preg_match('/\.(html|htm|php)$/', $path)) {
            $cleanPath = preg_replace('/\.(html|htm|php)$/', '', $path);
            if (Route::has($cleanPath)) {
                $suggestions[] = [
                    'reason' => 'Extension de fichier supprimée',
                    'original' => $path,
                    'suggested' => $cleanPath,
                    'url' => url($cleanPath),
                    'confidence' => 95
                ];
            }
        }

        return $suggestions;
    }
}
