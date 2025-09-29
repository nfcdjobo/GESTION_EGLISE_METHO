<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Private\Web\PermissionAuditLogController;
// Routes pour le système d'audit des permissions
// À ajouter dans routes/web.php dans le groupe admin

Route::prefix('admin')->middleware(['auth', 'permission:admin.access'])->name('private.')->group(function () {

    // Routes pour les logs d'audit des permissions
    Route::prefix('audit-logs')->name('audit.')->group(function () {

        // Route principale pour afficher la liste des logs
        Route::get('/', [PermissionAuditLogController::class, 'index'])->name('index');

        // Route pour afficher les détails d'un log spécifique
        Route::get('/{auditLog}', [PermissionAuditLogController::class, 'show'])->name('show');

        // Route pour afficher les statistiques des logs
        Route::get('/stats/dashboard', [PermissionAuditLogController::class, 'statistics'])->name('statistics');

        // Route pour afficher les logs d'un membres spécifique
        Route::get('/user/{user}/logs', [PermissionAuditLogController::class, 'userLogs'])->name('user.logs');

        // Route pour exporter les logs (CSV, JSON)
        Route::get('/export/data', [PermissionAuditLogController::class, 'export'])->name('export');

        // Route pour la recherche avancée dans les logs
        Route::post('/search/advanced', [PermissionAuditLogController::class, 'search'])->name('search');

        // Route pour obtenir les logs en temps réel (AJAX)
        Route::get('/realtime/feed', [PermissionAuditLogController::class, 'realtime'])->name('realtime');

        // Routes pour la gestion/nettoyage des logs (admin seulement)
        Route::middleware('permission:audit.manage')->group(function () {
            // Nettoyage des anciens logs
            Route::delete('/cleanup/old', [PermissionAuditLogController::class, 'cleanup'])->name('cleanup');

            // Suppression en lot des logs sélectionnés
            Route::delete('/bulk/delete', [PermissionAuditLogController::class, 'bulkDelete'])->name('bulk.delete');
        });
    });

    // Route alternative pour les logs d'audit accessible depuis le menu principal
    Route::get('/audit', [PermissionAuditLogController::class, 'index'])
        ->name('audit.dashboard');
});


