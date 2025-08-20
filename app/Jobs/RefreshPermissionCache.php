<?php
namespace App\Jobs;

use App\Models\User;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RefreshPermissionCache implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $fullRefresh;

    /**
     * Create a new job instance.
     */
    public function __construct($userId = null, $fullRefresh = false)
    {
        $this->userId = $userId;
        $this->fullRefresh = $fullRefresh;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            if ($this->fullRefresh) {
                // Rafraîchir le cache complet
                Cache::tags(['permissions', 'roles'])->flush();

                // Pré-charger les permissions et rôles fréquemment utilisés
                $this->preloadFrequentData();

                Log::info("Cache des permissions complètement rafraîchi");
            } elseif ($this->userId) {
                // Rafraîchir le cache d'un utilisateur spécifique
                $user = User::find($this->userId);
                if ($user) {
                    $user->clearPermissionsCache();
                    Cache::tags(["user_{$this->userId}"])->flush();

                    // Pré-charger les permissions de l'utilisateur
                    $user->getAllPermissions();

                    Log::info("Cache des permissions rafraîchi pour l'utilisateur", [
                        'user_id' => $this->userId,
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error("Erreur dans RefreshPermissionCache", [
                'error' => $e->getMessage(),
                'user_id' => $this->userId,
                'full_refresh' => $this->fullRefresh,
            ]);
            throw $e;
        }
    }

    /**
     * Pré-charger les données fréquemment utilisées
     */
    protected function preloadFrequentData(): void
    {
        // Pré-charger toutes les permissions actives
        $permissions = Permission::where('is_active', true)->get();
        Cache::tags(['permissions'])->put('all_active_permissions', $permissions, 3600);

        // Pré-charger tous les rôles
        $roles = Role::with('permissions')->get();
        Cache::tags(['roles'])->put('all_roles', $roles, 3600);

        // Pré-charger les statistiques
        Cache::tags(['permissions'])->put('system_stats', [
            'total_permissions' => Permission::count(),
            'active_permissions' => Permission::where('is_active', true)->count(),
            'total_roles' => Role::count(),
            'system_roles' => Role::where('is_system_role', true)->count(),
        ], 3600);
    }
}

