<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

trait DashboardHelper
{
    /**
     * Cache les résultats des requêtes lourdes
     */
    protected function cacheQuery($key, $callback, $minutes = 5)
    {
        $cacheKey = config('dashboard.cache.prefix', 'dashboard_') . $key;

        if (config('dashboard.cache.enabled', true)) {
            return Cache::remember($cacheKey, now()->addMinutes($minutes), $callback);
        }

        return $callback();
    }

    /**
     * Formate un nombre selon la configuration
     */
    protected function formatNumber($number, $includeDecimals = false)
    {
        if ($number === null) return '0';

        $config = config('dashboard.formats.number_format');
        $decimals = $includeDecimals ? 2 : $config['decimals'];

        return number_format(
            $number,
            $decimals,
            $config['decimal_separator'],
            $config['thousands_separator']
        );
    }

    /**
     * Formate une devise
     */
    protected function formatCurrency($amount, $currency = 'XOF')
    {
        return $this->formatNumber($amount) . ' ' . $currency;
    }

    /**
     * Calcule le pourcentage de croissance
     */
    protected function calculateGrowthPercentage($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }

    /**
     * Obtient la période actuelle et précédente
     */
    protected function getCurrentAndPreviousPeriod($period = 'month')
    {
        switch ($period) {
            case 'week':
                return [
                    'current_start' => Carbon::now()->startOfWeek(),
                    'current_end' => Carbon::now()->endOfWeek(),
                    'previous_start' => Carbon::now()->subWeek()->startOfWeek(),
                    'previous_end' => Carbon::now()->subWeek()->endOfWeek(),
                ];

            case 'month':
                return [
                    'current_start' => Carbon::now()->startOfMonth(),
                    'current_end' => Carbon::now()->endOfMonth(),
                    'previous_start' => Carbon::now()->subMonth()->startOfMonth(),
                    'previous_end' => Carbon::now()->subMonth()->endOfMonth(),
                ];

            case 'year':
                return [
                    'current_start' => Carbon::now()->startOfYear(),
                    'current_end' => Carbon::now()->endOfYear(),
                    'previous_start' => Carbon::now()->subYear()->startOfYear(),
                    'previous_end' => Carbon::now()->subYear()->endOfYear(),
                ];

            default:
                return $this->getCurrentAndPreviousPeriod('month');
        }
    }

    /**
     * Calcule les statistiques de base pour une table
     */
    protected function getBasicStats($table, $dateColumn = 'created_at', $conditions = [])
    {
        $query = DB::table($table)->whereNull('deleted_at');

        // Ajouter les conditions
        foreach ($conditions as $column => $value) {
            $query->where($column, $value);
        }

        $periods = $this->getCurrentAndPreviousPeriod('month');

        return [
            'total' => $query->count(),
            'current_month' => (clone $query)->whereBetween($dateColumn, [
                $periods['current_start'],
                $periods['current_end']
            ])->count(),
            'previous_month' => (clone $query)->whereBetween($dateColumn, [
                $periods['previous_start'],
                $periods['previous_end']
            ])->count(),
        ];
    }

    /**
     * Génère des données pour graphique en aires/lignes
     */
    protected function generateChartData($table, $valueColumn, $dateColumn = 'created_at', $months = 6, $conditions = [])
    {
        $data = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->format('M Y');

            $query = DB::table($table)
                ->whereMonth($dateColumn, $date->month)
                ->whereYear($dateColumn, $date->year)
                ->whereNull('deleted_at');

            // Ajouter les conditions
            foreach ($conditions as $column => $value) {
                $query->where($column, $value);
            }

            $value = $query->sum($valueColumn) ?? 0;

            $data[] = [
                'period' => $month,
                'value' => $value,
                'formatted_value' => $this->formatNumber($value)
            ];
        }

        return $data;
    }

    /**
     * Calcule le taux de completion moyen
     */
    protected function getAverageCompletionRate($table, $completionColumn, $conditions = [])
    {
        $query = DB::table($table)->whereNull('deleted_at');

        foreach ($conditions as $column => $value) {
            $query->where($column, $value);
        }

        $average = $query->avg($completionColumn) ?? 0;
        return round($average, 1);
    }

    /**
     * Obtient les éléments récents avec pagination
     */
    protected function getRecentItems($table, $columns = ['*'], $limit = 10, $orderBy = 'created_at', $conditions = [])
    {
        $query = DB::table($table)
            ->select($columns)
            ->whereNull('deleted_at');

        foreach ($conditions as $column => $value) {
            if (is_array($value)) {
                $query->whereIn($column, $value);
            } else {
                $query->where($column, $value);
            }
        }

        return $query->orderBy($orderBy, 'desc')
                    ->limit($limit)
                    ->get();
    }

    /**
     * Calcule les tendances (hausse/baisse)
     */
    protected function calculateTrend($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 'up' : 'stable';
        }

        $percentage = $this->calculateGrowthPercentage($current, $previous);

        if ($percentage > 5) return 'up';
        if ($percentage < -5) return 'down';
        return 'stable';
    }

    /**
     * Génère des couleurs pour les graphiques
     */
    protected function getChartColors($count = 1)
    {
        $colors = [
            '#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8',
            '#6f42c1', '#fd7e14', '#20c997', '#6c757d', '#e83e8c'
        ];

        if ($count == 1) {
            return $colors[0];
        }

        return array_slice($colors, 0, $count);
    }

    /**
     * Formate une période pour l'affichage
     */
    protected function formatPeriod($period, $format = 'M Y')
    {
        if (is_string($period)) {
            $period = Carbon::parse($period);
        }

        return $period->format($format);
    }

    /**
     * Calcule les statistiques financières
     */
    protected function getFinancialStats($dateRange = 'month')
    {
        $periods = $this->getCurrentAndPreviousPeriod($dateRange);

        $current = DB::table('transactions_spirituelles')
            ->where('statut', 'validee')
            ->whereBetween('date_transaction', [
                $periods['current_start'],
                $periods['current_end']
            ])
            ->whereNull('deleted_at')
            ->sum('montant') ?? 0;

        $previous = DB::table('transactions_spirituelles')
            ->where('statut', 'validee')
            ->whereBetween('date_transaction', [
                $periods['previous_start'],
                $periods['previous_end']
            ])
            ->whereNull('deleted_at')
            ->sum('montant') ?? 0;

        return [
            'current' => $current,
            'previous' => $previous,
            'growth' => $this->calculateGrowthPercentage($current, $previous),
            'trend' => $this->calculateTrend($current, $previous),
            'formatted_current' => $this->formatCurrency($current),
            'formatted_previous' => $this->formatCurrency($previous),
        ];
    }

    /**
     * Obtient les alertes basées sur des seuils
     */
    protected function getThresholdAlerts()
    {
        $alerts = [];

        // Vérifier les projets en retard
        $delayedProjects = DB::table('projets')
            ->where('date_fin_prevue', '<', Carbon::now())
            ->where('statut', 'en_cours')
            ->whereNull('deleted_at')
            ->count();

        if ($delayedProjects > 0) {
            $alerts[] = [
                'type' => 'warning',
                'message' => "Vous avez {$delayedProjects} projet(s) en retard",
                'icon' => 'fa-exclamation-triangle',
                'action_url' => route('admin.projets.index', ['filter' => 'delayed']),
                'action_text' => 'Voir les projets'
            ];
        }

        // Vérifier le taux de présence faible
        $attendanceRate = $this->calculateAttendanceRate();
        if ($attendanceRate < 50) {
            $alerts[] = [
                'type' => 'info',
                'message' => "Taux de présence faible: {$attendanceRate}%",
                'icon' => 'fa-users',
                'action_url' => route('admin.cultes.index'),
                'action_text' => 'Voir les cultes'
            ];
        }

        return $alerts;
    }

    /**
     * Génère un résumé exécutif
     */
    protected function getExecutiveSummary()
    {
        return $this->cacheQuery('executive_summary', function () {
            $memberStats = $this->getBasicStats('users', 'created_at', ['actif' => true]);
            $financialStats = $this->getFinancialStats('month');
            $eventStats = $this->getBasicStats('events', 'date_debut');

            return [
                'members' => [
                    'total' => $memberStats['total'],
                    'growth' => $this->calculateGrowthPercentage(
                        $memberStats['current_month'],
                        $memberStats['previous_month']
                    )
                ],
                'finances' => $financialStats,
                'events' => [
                    'total' => $eventStats['total'],
                    'this_month' => $eventStats['current_month']
                ],
                'generated_at' => Carbon::now()->format('d/m/Y H:i')
            ];
        }, 15); // Cache pour 15 minutes
    }

    /**
     * Nettoie le cache du dashboard
     */
    protected function clearDashboardCache()
    {
        $prefix = config('dashboard.cache.prefix', 'dashboard_');
        $keys = [
            'main_stats', 'ministry_stats', 'executive_summary',
            'financial_stats', 'performance_indicators'
        ];

        foreach ($keys as $key) {
            Cache::forget($prefix . $key);
        }

        return true;
    }

    /**
     * Valide les données avant affichage
     */
    protected function validateDashboardData($data)
    {
        // Vérifier que les données numériques sont valides
        foreach ($data as $key => $value) {
            if (is_numeric($value) && $value < 0) {
                $data[$key] = 0;
            }
        }

        return $data;
    }

    /**
     * Log les accès au dashboard pour audit
     */
    protected function logDashboardAccess($user_id, $section = 'main')
    {
        if (config('dashboard.security.audit_dashboard_access', true)) {
            DB::table('permission_audit_logs')->insert([
                'id' => \Illuminate\Support\Str::uuid(),
                'action' => 'dashboard_access',
                'model_type' => 'Dashboard',
                'model_id' => $section,
                'user_id' => $user_id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'context' => json_encode(['section' => $section]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
