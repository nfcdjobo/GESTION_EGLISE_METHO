<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Private\Web\UserController;
use App\Http\Controllers\Private\Web\AdminController;
use App\Http\Controllers\Private\UserPermissionController;

/*
|--------------------------------------------------------------------------
| Routes de gestion des utilisateurs
|--------------------------------------------------------------------------
|
| Routes protégées pour la gestion des utilisateurs avec système de permissions
|
*/

Route::middleware(['auth', 'user.status'])->prefix('dashboard')->name('private.')->group(function () {

    // ========== ROUTES CRUD PRINCIPALES ==========

    // Liste des utilisateurs
    Route::get('/users', [UserController::class, 'index'])->middleware('permission:users.read')->name('users.index');

    // Afficher le formulaire de création
    Route::get('/users/create', [UserController::class, 'create'])->middleware('permission:users.create')->name('users.create');


    // ========== ROUTES D'IMPORT/EXPORT ==========

    // Exporter les utilisateurs
    Route::get('/users/export', [UserController::class, 'export'])->middleware('permission:users.export')->name('users.export');

    // Afficher le formulaire d'import
    Route::get('/users/import', [UserController::class, 'import'])->middleware('permission:users.import')->name('users.import');

    // Traiter l'import de fichier
    Route::post('/users/import', [UserController::class, 'processImport'])->middleware('permission:users.import')->name('users.process-import');

    // ========== ROUTES AJAX ET API ==========

    // Recherche AJAX d'utilisateurs
    Route::get('/users/search', [UserController::class, 'search'])->middleware('permission:users.read')->name('users.search');

    // Enregistrer un nouvel utilisateur
    Route::post('/users', [UserController::class, 'store'])->middleware('permission:users.create')->name('users.store');

    // Afficher un utilisateur spécifique
    Route::get('/users/{user}', [UserController::class, 'show'])->middleware('permission:users.read')->name('users.show');

    // Afficher le formulaire d'édition
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->middleware('permission:users.update')->name('users.edit');

    // Mettre à jour un utilisateur
    Route::put('/users/{user}', [UserController::class, 'update'])->middleware('permission:users.update')->name('users.update');

    // Supprimer un utilisateur
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware('permission:users.delete')->name('users.destroy');

    // ========== ROUTES D'ACTIONS SPÉCIALES ==========

    // Valider un membre
    Route::post('/users/{user}/validate', [UserController::class, 'validated'])->middleware('permission:users.validate')->name('users.validate');

    // Archiver un utilisateur
    Route::post('/users/{user}/archive', [UserController::class, 'archive'])->middleware('permission:users.archive')->name('users.archive');

    // Restaurer un utilisateur archivé
    Route::post('/users/{user}/restore', [UserController::class, 'restore'])->middleware('permission:users.restore')->name('users.restore');

    // Changer le statut actif/inactif
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->middleware('permission:users.update')->name('users.toggle-status');

    // Réinitialiser le mot de passe
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->middleware('permission:users.update')->name('users.reset-password');



    // ========== ROUTES DE GESTION DES PERMISSIONS ==========

    // Gestion des permissions utilisateurs
    Route::prefix('users/{user}/permissions')->name('users.permissions.')->group(function () {
        Route::get('/', [UserPermissionController::class, 'index'])->middleware('permission:permissions.read')->name('index');

        Route::post('/grant', [UserPermissionController::class, 'grant'])->middleware('permission:permissions.grant')->name('grant');

        Route::post('/revoke', [UserPermissionController::class, 'revoke'])->middleware('permission:permissions.revoke')->name('revoke');

        Route::post('/sync', [UserPermissionController::class, 'sync'])->middleware('permission:permissions.sync')->name('sync');

        Route::get('/audit', [UserPermissionController::class, 'audit'])->middleware('permission:permissions.audit')->name('audit');

        Route::get('/expiring', [UserPermissionController::class, 'expiring'])->middleware('permission:permissions.read')->name('expiring');
    });

    // Gestion des rôles utilisateurs
    Route::prefix('users/{user}/roles')->name('users.roles.')->group(function () {
        Route::get('/', [UserPermissionController::class, 'roles'])->middleware('permission:roles.read')->name('index');

        Route::post('/assign', [UserPermissionController::class, 'assignRole'])->middleware('permission:roles.assign')->name('assign');

        Route::post('/remove', [UserPermissionController::class, 'removeRole'])->middleware('permission:roles.remove')->name('remove');

        Route::post('/sync', [UserPermissionController::class, 'syncRoles'])->middleware('permission:roles.sync')->name('sync');
    });
});

// ========== ROUTES SPÉCIALES AVEC PROTECTIONS PERSONNALISÉES ==========

// Protection par rôle pour l'administration
Route::middleware(['auth', 'user.status', 'role:admin,pasteur'])->name('private.')->group(function () {
    Route::get('/private/admin', [AdminController::class, 'index'])->name('admin.index');

    // Routes administratives avancées
    Route::prefix('dashboard/admin/users')->name('admin.users.')->group(function () {
        // Gestion avancée des utilisateurs (super admin seulement)
        Route::middleware('role:super-admin')->group(function () {
            Route::get('/deleted', [UserController::class, 'deleted'])->name('deleted');
            Route::post('/bulk-restore', [UserController::class, 'bulkRestore'])->name('bulk-restore');
            Route::post('/bulk-delete', [UserController::class, 'bulkDelete'])->name('bulk-delete');
            Route::post('/bulk-archive', [UserController::class, 'bulkArchive'])->name('bulk-archive');
        });

        // Statistiques et rapports
        Route::get('/statistics', [UserController::class, 'statistics'])->middleware('permission:users.statistics')->name('statistics');

        Route::get('/reports', [UserController::class, 'reports'])->middleware('permission:users.reports')->name('reports');
    });
});

// ========== ROUTES API POUR LES APPLICATIONS MOBILES ==========

Route::middleware(['auth:sanctum', 'user.status'])->prefix('api/v1')->name('api.v1.')->group(function () {
    // API de recherche rapide pour les applications mobiles
    Route::get('/users/quick-search', [UserController::class, 'apiQuickSearch'])->middleware('permission:users.read')->name('users.quick-search');

    // API pour récupérer les informations de base d'un utilisateur
    Route::get('/users/{user}/basic', [UserController::class, 'apiBasicInfo'])->middleware('permission:users.read')->name('users.basic-info');

    // API pour mettre à jour les informations de contact
    Route::patch('/users/{user}/contact', [UserController::class, 'apiUpdateContact'])->middleware('permission:users.update')->name('users.update-contact');
});

// ========== ROUTES PUBLIQUES (avec limitations) ==========

// Route pour la recherche publique limitée (pour les formulaires de contact par exemple)
Route::middleware(['throttle:60,1'])->group(function () {
    Route::get('/public/users/search-leaders', [UserController::class, 'publicSearchLeaders'])->name('public.users.search-leaders');
});

// ========== ROUTES DE DÉVELOPPEMENT (uniquement en local) ==========

if (app()->environment('local')) {
    Route::middleware(['auth', 'role:super-admin'])->prefix('dev')->name('dev.')->group(function () {
        // Routes pour les tests et le développement
        Route::get('/users/test-permissions', [UserController::class, 'testPermissions'])->name('users.test-permissions');
        Route::post('/users/seed-sample-data', [UserController::class, 'seedSampleData'])->name('users.seed-sample-data');
        Route::get('/users/debug-roles', [UserController::class, 'debugRoles'])->name('users.debug-roles');
    });
}

// ========== FALLBACK ET REDIRECTIONS ==========

// Redirection pour l'ancien système (si migration depuis un autre système)
Route::redirect('/members', '/private/users')->name('members.redirect');
Route::redirect('/admin/users', '/private/users')->name('admin.users.redirect');

// Route de fallback pour les erreurs 404 dans la section utilisateurs
Route::fallback(function () {
    return redirect()->route('private.users.index')->with('warning', 'Page non trouvée. Vous avez été redirigé vers la liste des utilisateurs.');
})->middleware(['auth', 'user.status']);

// ========== DÉFINITION DES CONTRAINTES DE ROUTES ==========

// Contraintes pour s'assurer que les paramètres sont corrects
Route::pattern('user', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}'); // UUID
Route::pattern('id', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}'); // UUID

/*
|--------------------------------------------------------------------------
| Routes supplémentaires pour la gestion des utilisateurs
|--------------------------------------------------------------------------
|
| Ces routes peuvent être ajoutées selon les besoins spécifiques
|
*/

// Groupe pour les routes nécessitant des permissions spéciales
Route::middleware(['auth', 'user.status', 'role:admin,pasteur,responsable'])->prefix('dashboard')->name('private.')->group(function () {

    // Routes pour les responsables de classes
    Route::prefix('classes/{classe}/members')->name('classes.members.')->group(function () {
        Route::get('/', [UserController::class, 'classeMembers'])->name('index');
        Route::post('/add', [UserController::class, 'addToClasse'])->name('add');
        Route::post('/remove', [UserController::class, 'removeFromClasse'])->name('remove');
    });

    // Routes pour les rapports de présence
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/users/{user}', [UserController::class, 'userAttendance'])->name('user');
        Route::post('/mark', [UserController::class, 'markAttendance'])->name('mark');
    });
});

// Groupe pour les notifications et communications
Route::middleware(['auth', 'user.status', 'permission:communications.send'])->prefix('dashboard')->name('private.')->group(function () {

    Route::prefix('communications')->name('communications.')->group(function () {
        // Envoyer des notifications à des utilisateurs spécifiques
        Route::post('/notify-users', [UserController::class, 'notifyUsers'])->name('notify-users');

        // Envoyer des emails groupés
        Route::post('/bulk-email', [UserController::class, 'bulkEmail'])->name('bulk-email');

        // Envoyer des SMS groupés
        Route::post('/bulk-sms', [UserController::class, 'bulkSms'])->name('bulk-sms');
    });
});

// Routes pour la synchronisation avec des systèmes externes
Route::middleware(['auth', 'role:super-admin'])->prefix('dashboard/sync')->name('private.sync.')->group(function () {
    Route::post('/users/external-system', [UserController::class, 'syncWithExternalSystem'])->name('users.external-system');
    Route::get('/users/sync-status', [UserController::class, 'getSyncStatus'])->name('users.sync-status');
});

/*
|--------------------------------------------------------------------------
| Documentation et aide
|--------------------------------------------------------------------------
*/

// Routes pour l'aide et la documentation
Route::middleware(['auth', 'user.status'])->prefix('dashboard/help')->name('private.help.')->group(function () {
    Route::get('/users', [UserController::class, 'helpUsers'])->name('users');
    Route::get('/permissions', [UserController::class, 'helpPermissions'])->name('permissions');
    Route::get('/import-guide', [UserController::class, 'importGuide'])->name('import-guide');
});
