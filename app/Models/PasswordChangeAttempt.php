<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class PasswordChangeAttempt extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'type',
        'ip_address',
        'user_agent',
        'reason',
        'attempted_at',
    ];

    protected $casts = [
        'attempted_at' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Vérifier si l'utilisateur peut changer son mot de passe
     */
    public static function canChangePassword($userId)
    {
        // Vérifier la dernière modification réussie (max 1 fois par mois)
        $lastSuccessfulChange = self::where('user_id', $userId)
            ->where('type', 'change_success')
            ->where('attempted_at', '>=', Carbon::now()->subMonth())
            ->exists();

        if ($lastSuccessfulChange) {
            return [
                'can_change' => false,
                'reason' => 'Vous avez déjà modifié votre mot de passe ce mois-ci. Vous devez attendre au moins 30 jours.',
                'next_allowed' => self::getNextAllowedChangeDate($userId)
            ];
        }

        // Vérifier les tentatives échouées cette semaine (max 4 par semaine)
        $failedAttemptsThisWeek = self::where('user_id', $userId)
            ->where('type', 'change_failed')
            ->where('attempted_at', '>=', Carbon::now()->startOfWeek())
            ->count();

        if ($failedAttemptsThisWeek >= 4) {
            return [
                'can_change' => false,
                'reason' => 'Vous avez atteint le nombre maximum de tentatives (4) pour cette semaine. Réessayez la semaine prochaine.',
                'next_allowed' => Carbon::now()->startOfWeek()->addWeek()
            ];
        }

        return [
            'can_change' => true,
            'remaining_attempts' => 4 - $failedAttemptsThisWeek
        ];
    }

    /**
     * Obtenir la prochaine date autorisée pour changer le mot de passe
     */
    public static function getNextAllowedChangeDate($userId)
    {
        $lastSuccess = self::where('user_id', $userId)
            ->where('type', 'change_success')
            ->orderBy('attempted_at', 'desc')
            ->first();

        if ($lastSuccess) {
            return $lastSuccess->attempted_at->addMonth();
        }

        return Carbon::now();
    }

    /**
     * Enregistrer une tentative
     */
    public static function recordAttempt($userId, $type, $reason = null)
    {
        return self::create([
            'user_id' => $userId,
            'type' => $type,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'reason' => $reason,
            'attempted_at' => Carbon::now(),
        ]);
    }
}
