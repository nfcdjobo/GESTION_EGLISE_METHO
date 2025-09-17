<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Error404Log extends Model
{
    use HasFactory;

    /**
     * Indique que la clé primaire n'est pas un entier auto-incrémenté
     */
    public $incrementing = false;

    /**
     * Le type de la clé primaire
     */
    protected $keyType = 'string';

    /**
     * Les attributs qui peuvent être assignés en masse
     */
    protected $fillable = [
        'url',
        'path',
        'method',
        'ip',
        'user_agent',
        'referrer',
        'user_id',
        'session_id',
        'request_data',
        'headers',
        'locale',
        'country_code',
        'city',
        'response_time',
        'is_bot',
        'is_mobile',
        'is_resolved',
        'resolution_type',
        'resolution_notes'
    ];

    /**
     * Les attributs qui doivent être castés
     */
    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
        'request_data' => 'array',
        'headers' => 'array',
        'is_bot' => 'boolean',
        'is_mobile' => 'boolean',
        'is_resolved' => 'boolean',
        'response_time' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Les attributs cachés pour la sérialisation
     */
    protected $hidden = [
        'request_data',
        'headers'
    ];

    /**
     * Boot du modèle pour générer automatiquement l'UUID
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }

            // Auto-détection du type de device et bot
            $userAgent = $model->user_agent ?? '';
            $model->is_mobile = $model->isMobileUserAgent($userAgent);
            $model->is_bot = $model->isBotUserAgent($userAgent);

            // Définir la locale si elle n'est pas définie
            if (empty($model->locale)) {
                $model->locale = app()->getLocale();
            }
        });
    }

    /**
     * Relation avec le modèle User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope pour filtrer par date
     */
    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope pour filtrer par période
     */
    public function scopeInLastDays(Builder $query, int $days): Builder
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope pour filtrer les erreurs non résolues
     */
    public function scopeUnresolved(Builder $query): Builder
    {
        return $query->where('is_resolved', false);
    }

    /**
     * Scope pour filtrer les bots
     */
    public function scopeNotFromBots(Builder $query): Builder
    {
        return $query->where('is_bot', false);
    }

    /**
     * Scope pour filtrer par chemin
     */
    public function scopeByPath(Builder $query, string $path): Builder
    {
        return $query->where('path', 'like', "%{$path}%");
    }

    /**
     * Obtient les URL les plus fréquentes
     */
    public static function getMostFrequentPaths(int $limit = 10, int $days = 30): \Illuminate\Support\Collection
    {
        return static::select('path', \DB::raw('count(*) as count'))
            ->inLastDays($days)
            ->notFromBots()
            ->groupBy('path')
            ->orderByDesc('count')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtient les statistiques par période
     */
    public static function getStatsByPeriod(string $period = 'day', int $limit = 7): \Illuminate\Support\Collection
    {
        $dateFormat = match($period) {
            'hour' => '%Y-%m-%d %H:00:00',
            'day' => '%Y-%m-%d',
            'week' => '%Y-%u',
            'month' => '%Y-%m',
            default => '%Y-%m-%d'
        };

        return static::select(
                \DB::raw("DATE_FORMAT(created_at, '{$dateFormat}') as period"),
                \DB::raw('count(*) as count'),
                \DB::raw('count(distinct ip) as unique_ips'),
                \DB::raw('count(distinct user_id) as unique_users')
            )
            ->inLastDays($limit * ($period === 'day' ? 1 : ($period === 'week' ? 7 : 30)))
            ->notFromBots()
            ->groupBy('period')
            ->orderBy('period', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Marque l'erreur comme résolue
     */
    public function markAsResolved(string $type = 'manual', string $notes = null): bool
    {
        return $this->update([
            'is_resolved' => true,
            'resolution_type' => $type,
            'resolution_notes' => $notes
        ]);
    }

    /**
     * Obtient le pays à partir du code pays
     */
    public function getCountryNameAttribute(): ?string
    {
        if (!$this->country_code) {
            return null;
        }

        $countries = [
            'CI' => 'Côte d\'Ivoire',
            'FR' => 'France',
            'US' => 'États-Unis',
            'CA' => 'Canada',
            'GB' => 'Royaume-Uni',
            'DE' => 'Allemagne',
            'ES' => 'Espagne',
            'IT' => 'Italie',
            // Ajouter d'autres pays selon vos besoins
        ];

        return $countries[$this->country_code] ?? $this->country_code;
    }

    /**
     * Obtient une version courte de l'URL
     */
    public function getShortUrlAttribute(): string
    {
        return Str::limit($this->url, 50);
    }

    /**
     * Formate le user agent pour l'affichage
     */
    public function getFormattedUserAgentAttribute(): string
    {
        if (!$this->user_agent) {
            return 'Inconnu';
        }

        // Extraire les informations principales du user agent
        preg_match('/^([^\/]+)/', $this->user_agent, $matches);
        $browser = $matches[1] ?? 'Inconnu';

        return Str::limit($browser, 30);
    }

    /**
     * Vérifie si le user agent correspond à un mobile
     */
    private function isMobileUserAgent(string $userAgent): bool
    {
        $mobileKeywords = [
            'Mobile', 'Android', 'iPhone', 'iPad', 'BlackBerry',
            'Windows Phone', 'Opera Mini', 'IEMobile'
        ];

        foreach ($mobileKeywords as $keyword) {
            if (stripos($userAgent, $keyword) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Vérifie si le user agent correspond à un bot
     */
    private function isBotUserAgent(string $userAgent): bool
    {
        $botKeywords = [
            'bot', 'crawler', 'spider', 'crawling', 'facebook', 'twitter',
            'google', 'yahoo', 'bing', 'baidu', 'yandex', 'duckduckgo'
        ];

        $userAgentLower = strtolower($userAgent);

        foreach ($botKeywords as $keyword) {
            if (strpos($userAgentLower, $keyword) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Supprime les anciens logs (nettoyage automatique)
     */
    public static function cleanOldLogs(int $daysToKeep = 90): int
    {
        return static::where('created_at', '<', now()->subDays($daysToKeep))->delete();
    }

    /**
     * Obtient les tendances d'erreurs 404
     */
    public static function getTrends(int $days = 30): array
    {
        $current = static::inLastDays($days)->notFromBots()->count();
        $previous = static::where('created_at', '>=', now()->subDays($days * 2))
                          ->where('created_at', '<', now()->subDays($days))
                          ->where('is_bot', false)
                          ->count();

        $change = $previous > 0 ? (($current - $previous) / $previous) * 100 : 0;

        return [
            'current_period' => $current,
            'previous_period' => $previous,
            'change_percentage' => round($change, 1),
            'trend' => $change > 0 ? 'increase' : ($change < 0 ? 'decrease' : 'stable')
        ];
    }
}
