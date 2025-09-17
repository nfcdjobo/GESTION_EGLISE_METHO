<?php

namespace App\Helpers;

use App\Models\Moisson;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MoissonCacheHelper
{
    // TTL par défaut pour les caches (en secondes)
    private const DEFAULT_TTL = 3600; // 1 heure
    private const STATS_TTL = 1800; // 30 minutes
    private const HEAVY_QUERY_TTL = 7200; // 2 heures

    // Tags pour organiser le cache
    private const CACHE_TAGS = [
        'moisson_stats',
        'moisson_data',
        'engagement_alerts',
        'performance_data'
    ];

    /**
     * Met en cache les statistiques globales
     */
    public static function cacheStatistiquesGlobales(?Carbon $dateDebut = null, ?Carbon $dateFin = null): array
    {
        $cacheKey = 'moisson_stats_global_' .
                   ($dateDebut ? $dateDebut->format('Y-m-d') : 'all') . '_' .
                   ($dateFin ? $dateFin->format('Y-m-d') : 'all');

        return Cache::tags(['moisson_stats'])->remember($cacheKey, self::STATS_TTL, function () use ($dateDebut, $dateFin) {
            return app(\App\Services\MoissonService::class)->obtenirStatistiquesGlobales($dateDebut, $dateFin);
        });
    }

    /**
     * Met en cache le tableau de bord d'une moisson
     */
    public static function cacheTableauDeBord(string $moissonId): array
    {
        $cacheKey = "moisson_dashboard_{$moissonId}";
// dd($cacheKey);
        return Cache::tags(['moisson_data'])->remember($cacheKey, self::DEFAULT_TTL, function () use ($moissonId) {
            return app(\App\Services\MoissonService::class)->obtenirTableauDeBord($moissonId);
        });
    }

    /**
     * Met en cache les rappels d'engagements
     */
    public static function cacheRappelsEngagements(): array
    {
        $cacheKey = 'engagement_rappels_' . now()->format('Y-m-d');

        return Cache::tags(['engagement_alerts'])->remember($cacheKey, 900, function () { // 15 minutes
            return app(\App\Services\MoissonService::class)->gererRappelsEngagements();
        });
    }

    /**
     * Met en cache les moissons récentes avec statistiques
     */
    public static function cacheMoissonsRecentes(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        $cacheKey = "moissons_recentes_{$limit}";

        return Cache::tags(['moisson_data'])->remember($cacheKey, self::DEFAULT_TTL, function () use ($limit) {
            return Moisson::with(['culte'])
                ->avecStatistiques()
                ->orderByDesc('date')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Met en cache les top performers
     */
    public static function cacheTopPerformers(int $limit = 5): \Illuminate\Database\Eloquent\Collection
    {
        $cacheKey = "moisson_top_performers_{$limit}";

        return Cache::tags(['performance_data'])->remember($cacheKey, self::HEAVY_QUERY_TTL, function () use ($limit) {
            return Moisson::selectRaw('
                *,
                ROUND((montant_solde * 100.0 / NULLIF(cible, 0)), 2) as performance_score
            ')
            ->where('status', true)
            ->orderByRaw('(montant_solde * 100.0 / NULLIF(cible, 0)) DESC')
            ->limit($limit)
            ->get();
        });
    }

    /**
     * Met en cache les statistiques par catégorie
     */
    public static function cacheStatistiquesParCategorie(string $type, ?string $moissonId = null): \Illuminate\Database\Eloquent\Collection
    {
        $cacheKey = "stats_categorie_{$type}_" . ($moissonId ?? 'global');

        return Cache::tags(['moisson_stats'])->remember($cacheKey, self::STATS_TTL, function () use ($type, $moissonId) {
            switch ($type) {
                case 'passages':
                    return \App\Models\PassageMoisson::statistiquesParCategorie($moissonId);
                case 'ventes':
                    return \App\Models\VenteMoisson::statistiquesParCategorie($moissonId);
                case 'engagements':
                    return \App\Models\EngagementMoisson::statistiquesGlobales($moissonId);
                default:
                    return collect();
            }
        });
    }

    /**
     * Met en cache les données pour les graphiques
     */
    public static function cacheDonneesGraphique(string $type, array $parametres = []): array
    {
        $cacheKey = "graph_data_{$type}_" . md5(json_encode($parametres));

        return Cache::tags(['performance_data'])->remember($cacheKey, self::HEAVY_QUERY_TTL, function () use ($type, $parametres) {
            switch ($type) {
                case 'evolution_mensuelle':
                    return self::getDonneesEvolutionMensuelle($parametres);

                case 'repartition_par_type':
                    return self::getDonneesRepartitionParType($parametres);

                case 'performance_comparative':
                    return self::getDonneesPerformanceComparative($parametres);

                default:
                    return [];
            }
        });
    }

    /**
     * Invalide tous les caches liés aux moissons
     */
    public static function invalidateAllCaches(): void
    {
        Cache::tags(self::CACHE_TAGS)->flush();
    }

    /**
     * Invalide le cache d'une moisson spécifique
     */
    public static function invalidateMoissonCache(string $moissonId): void
    {
        $keys = [
            "moisson_dashboard_{$moissonId}",
            "stats_categorie_passages_{$moissonId}",
            "stats_categorie_ventes_{$moissonId}",
            "stats_categorie_engagements_{$moissonId}"
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }

        // Invalider aussi les caches globaux qui pourraient être affectés
        Cache::tags(['moisson_stats', 'performance_data'])->flush();
    }

    /**
     * Invalide les caches de statistiques
     */
    public static function invalidateStatsCache(): void
    {
        Cache::tags(['moisson_stats', 'performance_data'])->flush();
    }

    /**
     * Met à jour la vue matérialisée avec cache
     */
    public static function refreshMaterializedView(): bool
    {
        $cacheKey = 'materialized_view_last_refresh';
        $lastRefresh = Cache::get($cacheKey, now()->subHours(2));

        // Ne rafraîchir que si la dernière fois date de plus d'une heure
        if ($lastRefresh->diffInMinutes(now()) >= 60) {
            try {
                DB::select('SELECT refresh_moisson_statistics()');
                Cache::put($cacheKey, now(), self::HEAVY_QUERY_TTL);
                return true;
            } catch (\Exception $e) {
                \Log::error('Erreur lors du rafraîchissement de la vue matérialisée: ' . $e->getMessage());
                return false;
            }
        }

        return false; // Pas besoin de rafraîchir
    }

    /**
     * Obtient les statistiques avec mise en cache automatique
     */
    public static function getStatsAvecCache(string $type, array $params = []): array
    {
        $cacheKey = "stats_{$type}_" . md5(json_encode($params));

        return Cache::tags(['moisson_stats'])->remember($cacheKey, self::STATS_TTL, function () use ($type, $params) {
            switch ($type) {
                case 'resumé_global':
                    return self::calculateGlobalSummary();

                case 'tendances_mensuelles':
                    return self::calculateMonthlyTrends($params);

                case 'alertes_engagements':
                    return self::calculateEngagementAlerts();

                default:
                    return [];
            }
        });
    }

    // Méthodes privées pour les calculs

    private static function getDonneesEvolutionMensuelle(array $params): array
    {
        $mois = $params['mois'] ?? 12;

        return Moisson::selectRaw('
            EXTRACT(YEAR FROM date) as annee,
            EXTRACT(MONTH FROM date) as mois,
            COUNT(*) as nombre_moissons,
            SUM(cible) as total_objectifs,
            SUM(montant_solde) as total_collecte,
            ROUND(AVG(montant_solde * 100.0 / NULLIF(cible, 0)), 2) as pourcentage_moyen
        ')
        ->where('date', '>=', now()->subMonths($mois))
        ->groupByRaw('EXTRACT(YEAR FROM date), EXTRACT(MONTH FROM date)')
        ->orderByRaw('annee DESC, mois DESC')
        ->get()
        ->toArray();
    }

    private static function getDonneesRepartitionParType(array $params): array
    {
        $moissonId = $params['moisson_id'] ?? null;

        $passages = \App\Models\PassageMoisson::when($moissonId, fn($q) => $q->where('moisson_id', $moissonId))
            ->selectRaw('SUM(montant_solde) as total, \'Passages\' as type')
            ->first();

        $ventes = \App\Models\VenteMoisson::when($moissonId, fn($q) => $q->where('moisson_id', $moissonId))
            ->selectRaw('SUM(montant_solde) as total, \'Ventes\' as type')
            ->first();

        $engagements = \App\Models\EngagementMoisson::when($moissonId, fn($q) => $q->where('moisson_id', $moissonId))
            ->selectRaw('SUM(montant_solde) as total, \'Engagements\' as type')
            ->first();

        return [
            ['type' => 'Passages', 'montant' => $passages->total ?? 0],
            ['type' => 'Ventes', 'montant' => $ventes->total ?? 0],
            ['type' => 'Engagements', 'montant' => $engagements->total ?? 0]
        ];
    }

    private static function getDonneesPerformanceComparative(array $params): array
    {
        $limit = $params['limit'] ?? 10;

        return Moisson::selectRaw('
            theme,
            cible,
            montant_solde,
            ROUND((montant_solde * 100.0 / NULLIF(cible, 0)), 2) as pourcentage,
            date
        ')
        ->where('status', true)
        ->orderByDesc('montant_solde')
        ->limit($limit)
        ->get()
        ->toArray();
    }

    private static function calculateGlobalSummary(): array
    {
        return [
            'total_moissons' => Moisson::count(),
            'moissons_actives' => Moisson::where('status', true)->count(),
            'objectif_total' => Moisson::sum('cible'),
            'collecte_totale' => Moisson::sum('montant_solde'),
            'performance_globale' => Moisson::selectRaw('ROUND(AVG(montant_solde * 100.0 / NULLIF(cible, 0)), 2) as avg')->first()->avg ?? 0
        ];
    }

    private static function calculateMonthlyTrends(array $params): array
    {
        $derniersMois = $params['mois'] ?? 6;

        return Moisson::selectRaw('
            DATE_TRUNC(\'month\', date) as mois,
            COUNT(*) as nombre,
            AVG(montant_solde * 100.0 / NULLIF(cible, 0)) as performance_moyenne
        ')
        ->where('date', '>=', now()->subMonths($derniersMois))
        ->groupByRaw('DATE_TRUNC(\'month\', date)')
        ->orderBy('mois')
        ->get()
        ->toArray();
    }

    private static function calculateEngagementAlerts(): array
    {
        return [
            'retards_critiques' => \App\Models\EngagementMoisson::enRetard()
                ->parNiveauUrgence('critique')
                ->count(),
            'rappels_du_jour' => \App\Models\EngagementMoisson::aRappeler()
                ->count(),
            'echeances_proches' => \App\Models\EngagementMoisson::where('date_echeance', '<=', now()->addDays(7))
                ->where('reste', '>', 0)
                ->count()
        ];
    }
}
