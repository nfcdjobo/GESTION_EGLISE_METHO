<?php


namespace App\Jobs;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GeneratePermissionReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $type;
    protected $format;
    protected $email;

    /**
     * Create a new job instance.
     */
    public function __construct($type = 'full', $format = 'csv', $email = null)
    {
        $this->type = $type;
        $this->format = $format;
        $this->email = $email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $report = match ($this->type) {
                'permissions' => $this->generatePermissionsReport(),
                'roles' => $this->generateRolesReport(),
                'users' => $this->generateUsersReport(),
                'audit' => $this->generateAuditReport(),
                default => $this->generateFullReport(),
            };

            $filename = "permission_report_{$this->type}_" . date('Y-m-d_H-i-s') . ".{$this->format}";
            $path = "reports/permissions/{$filename}";

            // Sauvegarder le rapport
            Storage::disk('local')->put($path, $report);

            // Envoyer par email si demandé
            if ($this->email) {
                // Implémenter l'envoi par email
                // Mail::to($this->email)->send(new PermissionReportGenerated($path));
            }

            Log::info("Rapport de permissions généré", [
                'type' => $this->type,
                'format' => $this->format,
                'path' => $path,
                'email' => $this->email,
            ]);
        } catch (\Exception $e) {
            Log::error("Erreur dans GeneratePermissionReport", [
                'error' => $e->getMessage(),
                'type' => $this->type,
            ]);
            throw $e;
        }
    }

    /**
     * Générer un rapport complet
     */
    protected function generateFullReport(): string
    {
        $sections = [
            $this->generatePermissionsReport(),
            $this->generateRolesReport(),
            $this->generateUsersReport(),
            $this->generateAuditReport(),
        ];

        return implode("\n\n", $sections);
    }

    /**
     * Générer le rapport des permissions
     */
    protected function generatePermissionsReport(): string
    {
        $permissions = Permission::with(['roles', 'users'])->get();

        if ($this->format === 'csv') {
            $csv = "RAPPORT DES PERMISSIONS\n";
            $csv .= "Généré le : " . date('d/m/Y H:i:s') . "\n\n";
            $csv .= "ID,Nom,Slug,Ressource,Action,Catégorie,Actif,Système,Nb Rôles,Nb Membress,Dernière utilisation\n";

            foreach ($permissions as $permission) {
                $csv .= sprintf(
                    '"%s","%s","%s","%s","%s","%s","%s","%s","%d","%d","%s"' . "\n",
                    $permission->id,
                    $permission->name,
                    $permission->slug,
                    $permission->resource ?? '',
                    $permission->action,
                    $permission->category ?? '',
                    $permission->is_active ? 'Oui' : 'Non',
                    $permission->is_system ? 'Oui' : 'Non',
                    $permission->roles->count(),
                    $permission->users->count(),
                    $permission->last_used_at ? $permission->last_used_at->format('d/m/Y H:i') : 'Jamais'
                );
            }

            return $csv;
        }

        // Format JSON
        return json_encode([
            'type' => 'permissions_report',
            'generated_at' => now()->toIso8601String(),
            'permissions' => $permissions->map(function ($permission) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'slug' => $permission->slug,
                    'resource' => $permission->resource,
                    'action' => $permission->action,
                    'category' => $permission->category,
                    'is_active' => $permission->is_active,
                    'is_system' => $permission->is_system,
                    'roles_count' => $permission->roles->count(),
                    'users_count' => $permission->users->count(),
                    'last_used_at' => $permission->last_used_at,
                ];
            }),
        ], JSON_PRETTY_PRINT);
    }

    /**
     * Générer le rapport des rôles
     */
    protected function generateRolesReport(): string
    {
        $roles = Role::with(['permissions', 'users'])->get();

        if ($this->format === 'csv') {
            $csv = "RAPPORT DES RÔLES\n";
            $csv .= "Généré le : " . date('d/m/Y H:i:s') . "\n\n";
            $csv .= "ID,Nom,Slug,Niveau,Système,Nb Membress,Nb Permissions\n";

            foreach ($roles as $role) {
                $csv .= sprintf(
                    '"%s","%s","%s","%d","%s","%d","%d"' . "\n",
                    $role->id,
                    $role->name,
                    $role->slug,
                    $role->level,
                    $role->is_system_role ? 'Oui' : 'Non',
                    $role->users->count(),
                    $role->permissions->count()
                );
            }

            return $csv;
        }

        return json_encode([
            'type' => 'roles_report',
            'generated_at' => now()->toIso8601String(),
            'roles' => $roles,
        ], JSON_PRETTY_PRINT);
    }

    /**
     * Générer le rapport des membres
     */
    protected function generateUsersReport(): string
    {
        $users = User::with(['roles', 'permissions'])
            ->withCount(['roles', 'permissions'])
            ->get();

        if ($this->format === 'csv') {
            $csv = "RAPPORT DES UTILISATEURS ET PERMISSIONS\n";
            $csv .= "Généré le : " . date('d/m/Y H:i:s') . "\n\n";
            $csv .= "ID,Nom,Email,Actif,Nb Rôles,Nb Permissions directes,Rôles,Niveau max\n";

            foreach ($users as $user) {
                $csv .= sprintf(
                    '"%s","%s","%s","%s","%d","%d","%s","%s"' . "\n",
                    $user->id,
                    $user->nom_complet,
                    $user->email,
                    $user->actif ? 'Oui' : 'Non',
                    $user->roles_count,
                    $user->permissions_count,
                    $user->roles->pluck('name')->implode(', '),
                    $user->getHighestRoleLevel() ?? 'N/A'
                );
            }

            return $csv;
        }

        return json_encode([
            'type' => 'users_report',
            'generated_at' => now()->toIso8601String(),
            'users' => $users,
        ], JSON_PRETTY_PRINT);
    }

    /**
     * Générer le rapport d'audit
     */
    protected function generateAuditReport(): string
    {
        $recentChanges = DB::table('user_permissions')
            ->join('users', 'user_permissions.user_id', '=', 'users.id')
            ->join('permissions', 'user_permissions.permission_id', '=', 'permissions.id')
            ->select(
                'users.nom',
                'users.prenom',
                'permissions.name as permission_name',
                'user_permissions.is_granted',
                'user_permissions.granted_at',
                'user_permissions.revoked_at',
                'user_permissions.reason'
            )
            ->where('user_permissions.created_at', '>=', now()->subDays(30))
            ->orderBy('user_permissions.created_at', 'desc')
            ->limit(100)
            ->get();

        if ($this->format === 'csv') {
            $csv = "RAPPORT D'AUDIT - 30 DERNIERS JOURS\n";
            $csv .= "Généré le : " . date('d/m/Y H:i:s') . "\n\n";
            $csv .= "Membres,Permission,Action,Date,Raison\n";

            foreach ($recentChanges as $change) {
                $action = $change->is_granted ? 'Accordée' : 'Révoquée';
                $date = $change->is_granted ? $change->granted_at : $change->revoked_at;

                $csv .= sprintf(
                    '"%s","%s","%s","%s","%s"' . "\n",
                    $change->prenom . ' ' . $change->nom,
                    $change->permission_name,
                    $action,
                    $date,
                    $change->reason ?? ''
                );
            }

            return $csv;
        }

        return json_encode([
            'type' => 'audit_report',
            'generated_at' => now()->toIso8601String(),
            'period' => '30_days',
            'changes' => $recentChanges,
        ], JSON_PRETTY_PRINT);
    }
}
