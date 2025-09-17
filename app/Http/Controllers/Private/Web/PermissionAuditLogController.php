<?php

namespace App\Http\Controllers\Private\Web;

use App\Http\Controllers\Controller;
use App\Models\PermissionAuditLog;
use App\Models\User;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PermissionAuditLogController extends Controller
{

    public function __construct()
{
    $this->middleware('auth');
    $this->middleware('permission:audit.read')->only(['index', 'show', 'statistics', 'userLogs', 'search', 'realtime']);
    $this->middleware('permission:audit.export')->only(['export']);
    $this->middleware('permission:audit.manage')->only(['cleanup', 'bulkDelete']);
}

    /**
     * Afficher la liste des logs d'audit
     */
    public function index(Request $request)
    {
        $query = PermissionAuditLog::with(['user', 'targetUser']);

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('model_type', 'like', "%{$search}%")
                  ->orWhere('model_id', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($subQ) use ($search) {
                      $subQ->where('nom', 'like', "%{$search}%")
                           ->orWhere('prenom', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('targetUser', function ($subQ) use ($search) {
                      $subQ->where('nom', 'like', "%{$search}%")
                           ->orWhere('prenom', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('target_user_id')) {
            $query->where('target_user_id', $request->target_user_id);
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', Carbon::parse($request->date_from)->startOfDay());
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', Carbon::parse($request->date_to)->endOfDay());
        }

        // Filtres prédéfinis
        if ($request->filled('period')) {
            switch ($request->period) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'yesterday':
                    $query->whereDate('created_at', today()->subDay());
                    break;
                case 'last_7_days':
                    $query->where('created_at', '>=', now()->subDays(7));
                    break;
                case 'last_30_days':
                    $query->where('created_at', '>=', now()->subDays(30));
                    break;
                case 'this_month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
                case 'last_month':
                    $query->whereMonth('created_at', now()->subMonth()->month)
                          ->whereYear('created_at', now()->subMonth()->year);
                    break;
            }
        }

        // Actions critiques
        if ($request->boolean('critical_only')) {
            $query->whereIn('action', [
                'deleted', 'permission_revoked', 'role_removed', 'permission_removed'
            ]);
        }

        // Tri
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        $allowedSorts = ['created_at', 'action', 'model_type', 'user_id', 'target_user_id'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }


        $perPage = $request->get('per_page', 25);
        $logs = $query->paginate($perPage)->appends(request()->query());


        // Données pour les filtres
        $actions = PermissionAuditLog::distinct()->pluck('action')->filter()->values();
        $modelTypes = PermissionAuditLog::distinct()->pluck('model_type')->filter()->values();
        $users = User::select('id', 'nom', 'prenom', 'email')
                    //  ->whereHas('auditLogs')
                     ->orderBy('nom')
                     ->get();

        return view('components.private.auditlogs.index', compact(
            'logs',
            'actions',
            'modelTypes',
            'users'
        ));
    }

    /**
     * Afficher les détails d'un log d'audit
     */
    public function show(PermissionAuditLog $auditLog)
    {
        $auditLog->load(['user', 'targetUser']);

        // Récupérer le modèle lié si possible
        $relatedModel = null;
        if ($auditLog->model_type && $auditLog->model_id) {
            $modelClass = "App\\Models\\{$auditLog->model_type}";
            if (class_exists($modelClass)) {
                try {
                    $relatedModel = $modelClass::withTrashed()->find($auditLog->model_id);
                } catch (\Exception $e) {
                    // Le modèle n'existe plus ou erreur
                }
            }
        }

        // Logs connexes (même membres, même période)
        $relatedLogs = PermissionAuditLog::where('id', '!=', $auditLog->id)
            ->where('user_id', $auditLog->user_id)
            ->where('created_at', '>=', $auditLog->created_at->subMinutes(5))
            ->where('created_at', '<=', $auditLog->created_at->addMinutes(5))
            ->with(['user', 'targetUser'])
            ->orderBy('created_at')
            ->limit(10)
            ->get();

        return view('components.private.auditlogs.show', compact('auditLog', 'relatedModel', 'relatedLogs'));
    }

    /**
     * Afficher les statistiques des logs d'audit
     */
    public function statistics(Request $request)
    {
        $period = $request->get('period', 30); // Jours
        $stats = PermissionAuditLog::getStatistics($period);

        // Statistiques supplémentaires
        $additionalStats = [
            'logs_by_day' => PermissionAuditLog::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays($period))
                ->groupBy('date')
                ->orderBy('date')
                ->get(),

            'critical_actions_trend' => PermissionAuditLog::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->whereIn('action', ['deleted', 'permission_revoked', 'role_removed'])
                ->where('created_at', '>=', now()->subDays($period))
                ->groupBy('date')
                ->orderBy('date')
                ->get(),

            'top_target_users' => PermissionAuditLog::whereNotNull('target_user_id')
                ->where('created_at', '>=', now()->subDays($period))
                ->selectRaw('target_user_id, COUNT(*) as count')
                ->groupBy('target_user_id')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->with('targetUser')
                ->get(),

            'hourly_distribution' => PermissionAuditLog::selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays($period))
                ->groupBy('hour')
                ->orderBy('hour')
                ->get(),
        ];

        return view('components.private.auditlogs.statistics', compact('stats', 'additionalStats', 'period'));
    }

    /**
     * Afficher les logs d'un membres spécifique
     */
    public function userLogs(Request $request, User $user)
    {
        $query = PermissionAuditLog::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere('target_user_id', $user->id);
        })->with(['user', 'targetUser']);

        // Filtres spécifiques
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('role_type')) {
            if ($request->role_type === 'actor') {
                $query->where('user_id', $user->id);
            } elseif ($request->role_type === 'target') {
                $query->where('target_user_id', $user->id);
            }
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', Carbon::parse($request->date_from));
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', Carbon::parse($request->date_to));
        }

        $logs = $query->orderBy('created_at', 'desc')
                     ->paginate(20)
                     ->withQueryString();

        // Statistiques de l'membres
        $userStats = [
            'total_actions_performed' => PermissionAuditLog::where('user_id', $user->id)->count(),
            'total_actions_received' => PermissionAuditLog::where('target_user_id', $user->id)->count(),
            'last_action_performed' => PermissionAuditLog::where('user_id', $user->id)->latest()->first(),
            'last_action_received' => PermissionAuditLog::where('target_user_id', $user->id)->latest()->first(),
            'most_common_actions' => PermissionAuditLog::where('user_id', $user->id)
                ->selectRaw('action, COUNT(*) as count')
                ->groupBy('action')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get(),
        ];

        return view('components.private.auditlogs.userlogs', compact('user', 'logs', 'userStats'));
    }

    /**
     * Exporter les logs d'audit
     */
    public function export(Request $request)
    {
        Gate::authorize('export-data', 'audit-logs');

        $query = PermissionAuditLog::with(['user', 'targetUser']);

        // Appliquer les mêmes filtres que l'index
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', Carbon::parse($request->date_from));
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', Carbon::parse($request->date_to));
        }

        if ($request->boolean('critical_only')) {
            $query->whereIn('action', [
                'deleted', 'permission_revoked', 'role_removed', 'permission_removed'
            ]);
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        $format = $request->get('format', 'csv');

        switch ($format) {
            case 'json':
                return response()->json($logs);

            default: // CSV
                $csv = "Date/Heure,Action,Type de modèle,ID du modèle,Membres,Membres cible,Adresse IP,Description\n";

                foreach ($logs as $log) {
                    $csv .= sprintf(
                        '"%s","%s","%s","%s","%s","%s","%s","%s"' . "\n",
                        $log->created_at->format('Y-m-d H:i:s'),
                        $log->action_name,
                        $log->model_type,
                        $log->model_id,
                        $log->user ? $log->user->nom_complet : 'Système',
                        $log->targetUser ? $log->targetUser->nom_complet : 'N/A',
                        $log->ip_address ?? 'N/A',
                        str_replace('"', '""', $log->description)
                    );
                }

                return response($csv)
                    ->header('Content-Type', 'text/csv; charset=utf-8')
                    ->header('Content-Disposition', 'attachment; filename="audit_logs_' . date('Y-m-d_H-i-s') . '.csv"');
        }
    }

    /**
     * Nettoyer les anciens logs
     */
    public function cleanup(Request $request)
    {
        Gate::authorize('manage-system', 'audit-logs');

        $validated = $request->validate([
            'older_than_days' => 'required|integer|min:1|max:3650', // Max 10 ans
            'keep_critical' => 'boolean',
            'dry_run' => 'boolean',
        ]);

        $cutoffDate = now()->subDays($validated['older_than_days']);

        $query = PermissionAuditLog::where('created_at', '<', $cutoffDate);

        // Garder les actions critiques si demandé
        if ($validated['keep_critical'] ?? false) {
            $query->whereNotIn('action', [
                'deleted', 'permission_revoked', 'role_removed', 'permission_removed'
            ]);
        }

        $count = $query->count();

        if ($validated['dry_run'] ?? false) {
            return response()->json([
                'success' => true,
                'message' => "Mode test: {$count} logs seraient supprimés",
                'count' => $count,
                'dry_run' => true
            ]);
        }

        try {
            $deleted = $query->delete();

            return response()->json([
                'success' => true,
                'message' => "{$deleted} logs supprimés avec succès",
                'count' => $deleted
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Suppression en lot
     */
    public function bulkDelete(Request $request)
    {
        Gate::authorize('manage-system', 'audit-logs');

        $validated = $request->validate([
            'log_ids' => 'required|array',
            'log_ids.*' => 'exists:permission_audit_logs,id'
        ]);

        try {
            $count = PermissionAuditLog::whereIn('id', $validated['log_ids'])->delete();

            return response()->json([
                'success' => true,
                'message' => "{$count} logs supprimés avec succès",
                'count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Recherche avancée dans les logs
     */
    public function search(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required|string|min:2',
            'fields' => 'array',
            'fields.*' => 'in:action,model_type,user,target_user,ip_address,changes'
        ]);

        $searchQuery = $validated['query'];
        $searchFields = $validated['fields'] ?? ['action', 'model_type', 'user', 'target_user'];

        $query = PermissionAuditLog::with(['user', 'targetUser']);

        $query->where(function ($q) use ($searchQuery, $searchFields) {
            if (in_array('action', $searchFields)) {
                $q->orWhere('action', 'like', "%{$searchQuery}%");
            }

            if (in_array('model_type', $searchFields)) {
                $q->orWhere('model_type', 'like', "%{$searchQuery}%");
            }

            if (in_array('ip_address', $searchFields)) {
                $q->orWhere('ip_address', 'like', "%{$searchQuery}%");
            }

            if (in_array('changes', $searchFields)) {
                $q->orWhereJsonContains('changes', $searchQuery)
                  ->orWhereJsonContains('original', $searchQuery);
            }

            if (in_array('user', $searchFields)) {
                $q->orWhereHas('user', function ($subQ) use ($searchQuery) {
                    $subQ->where('nom', 'like', "%{$searchQuery}%")
                         ->orWhere('prenom', 'like', "%{$searchQuery}%")
                         ->orWhere('email', 'like', "%{$searchQuery}%");
                });
            }

            if (in_array('target_user', $searchFields)) {
                $q->orWhereHas('targetUser', function ($subQ) use ($searchQuery) {
                    $subQ->where('nom', 'like', "%{$searchQuery}%")
                         ->orWhere('prenom', 'like', "%{$searchQuery}%")
                         ->orWhere('email', 'like', "%{$searchQuery}%");
                });
            }
        });

        $results = $query->orderBy('created_at', 'desc')
                        ->paginate(20)
                        ->withQueryString();

        return response()->json([
            'success' => true,
            'data' => $results,
            'query' => $searchQuery,
            'fields' => $searchFields
        ]);
    }

    /**
     * Obtenir les logs en temps réel (pour tableau de bord)
     */
    public function realtime(Request $request)
    {
        $lastId = $request->get('last_id', 0);
        $limit = min($request->get('limit', 10), 50); // Max 50

        $logs = PermissionAuditLog::with(['user', 'targetUser'])
            ->where('id', '>', $lastId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $logs,
            'last_id' => $logs->isNotEmpty() ? $logs->first()->id : $lastId
        ]);
    }
}
