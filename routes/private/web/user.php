<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Private\Web\UserController;
use App\Http\Controllers\Private\Web\AdminController;
use App\Http\Controllers\Private\UserPermissionController;

/*
|--------------------------------------------------------------------------
| Routes de Gestion des membres
|--------------------------------------------------------------------------
|
| Routes protégées pour la Gestion des membres avec système de permissions
|
*/

Route::middleware(['auth', 'user.status'])->prefix('users')->name('private.users.')->group(function () {

    // ========== ROUTES CRUD PRINCIPALES ==========

    // Liste des membres
    Route::get('', [UserController::class, 'index'])->middleware('permission:users.read')->name('index');

    // Afficher le formulaire de création
    Route::get('/create', [UserController::class, 'create'])->middleware('permission:users.create')->name('create');

    // Exporter les membres
    Route::get('/export', [UserController::class, 'export'])->middleware('permission:users.export')->name('export');

    // Afficher le formulaire d'import
    Route::get('/import', [UserController::class, 'import'])->middleware('permission:users.import')->name('import');

    // Traiter l'import de fichier
    Route::post('/import', [UserController::class, 'processImport'])->middleware('permission:users.import')->name('process-import');

    // Recherche AJAX d'membres
    Route::get('/search', [UserController::class, 'search'])->middleware('permission:users.read')->name('search');

    // Enregistrer un nouvel membres
    Route::post('', [UserController::class, 'store'])->middleware('permission:users.create')->name('store');
    Route::post('/ajoutmembre', [UserController::class, 'ajoutmembre'])->middleware('permission:users.ajoutmembre')->name('ajoutmembre');

    // Afficher un membres spécifique
    Route::get('/{user}', [UserController::class, 'show'])->middleware('permission:users.read')->name('show');

    Route::get('/{fimeco}/notsubscribedtofimeco', [UserController::class, 'usersNotSubscribedToFimeco'])->middleware(['permission:users.read', 'permission:fimecos.update'])->name('not-subscribed-to-fimeco');

    // Afficher le formulaire d'édition
    Route::get('/{user}/edit', [UserController::class, 'edit'])->middleware('permission:users.update')->name('edit');

    // Mettre à jour un membres
    Route::put('/{user}', [UserController::class, 'update'])->middleware('permission:users.update')->name('update');

    // Supprimer un membres
    Route::delete('/{user}', [UserController::class, 'destroy'])->middleware('permission:users.delete')->name('destroy');

    // ========== ROUTES D'ACTIONS SPÉCIALES ==========

    // Valider un membre
    Route::post('/{user}/validate', [UserController::class, 'validated'])->middleware('permission:users.validate')->name('validate');

    // Archiver un membres
    Route::post('/{user}/archive', [UserController::class, 'archive'])->middleware('permission:users.archive')->name('archive');

    // Restaurer un membres archivé
    Route::post('/{user}/restore', [UserController::class, 'restore'])->middleware('permission:users.restore')->name('restore');

    // Changer le statut actif/inactif
    Route::post('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->middleware('permission:users.update')->name('toggle-status');

    // Réinitialiser le mot de passe
    Route::post('/{user}/reset-password', [UserController::class, 'resetPassword'])->middleware('permission:users.update')->name('reset-password');

    // Gestion des permissions membres
    Route::prefix('/{user}/permissions')->name('permissions.')->group(function () {
        Route::get('/', [UserPermissionController::class, 'index'])->middleware('permission:permissions.read')->name('index');

        Route::post('/grant', [UserPermissionController::class, 'grant'])->middleware('permission:permissions.grant')->name('grant');

        Route::post('/revoke', [UserPermissionController::class, 'revoke'])->middleware('permission:permissions.revoke')->name('revoke');

        Route::post('/sync', [UserPermissionController::class, 'sync'])->middleware('permission:permissions.sync')->name('sync');

        Route::get('/audit', [UserPermissionController::class, 'audit'])->middleware('permission:permissions.audit')->name('audit');

        Route::get('/expiring', [UserPermissionController::class, 'expiring'])->middleware('permission:permissions.read')->name('expiring');
    });

    // Gestion des rôles membres
    Route::prefix('/{user}/roles')->name('roles.')->group(function () {
        Route::get('/', [UserPermissionController::class, 'roles'])->middleware('permission:roles.read')->name('index');

        Route::post('/assign', [UserPermissionController::class, 'assignRole'])->middleware('permission:roles.assign')->name('assign');

        Route::post('/remove', [UserPermissionController::class, 'removeRole'])->middleware('permission:roles.remove')->name('remove');

        Route::post('/sync', [UserPermissionController::class, 'syncRoles'])->middleware('permission:roles.sync')->name('sync');
    });
});



// Contraintes pour s'assurer que les paramètres sont corrects
Route::pattern('user', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}'); // UUID
Route::pattern('id', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}'); // UUID


