<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\private\Web\RoleController;

// Routes d'administration des permissions
Route::middleware(['auth', 'user.status'])->prefix('dashboard')->name('private.')->group(function () {

    // Gestion des rôles
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->middleware('permission:roles.read')->name('index');
        Route::get('/create', [RoleController::class, 'create'])->middleware('permission:roles.create')->name('create');
        Route::post('/', [RoleController::class, 'store'])->middleware('permission:roles.create')->name('store');
        Route::get('/hierarchy', [RoleController::class, 'hierarchy'])->middleware('permission:roles.hierarchy')->name('hierarchy');
        Route::post('/compare', [RoleController::class, 'compare'])->middleware('permission:roles.compare')->name('compare');
        Route::get('/export/csv', [RoleController::class, 'export'])->middleware('permission:roles.export')->name('export');
        Route::get('/{role}', [RoleController::class, 'show'])->middleware('permission:roles.read')->name('show');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->middleware('permission:roles.update')->name('edit');
        Route::put('/{role}', [RoleController::class, 'update'])->middleware('permission:roles.update')->name('update');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->middleware('permission:roles.delete')->name('destroy');
        Route::post('/{role}/clone', [RoleController::class, 'clone'])->middleware('permission:roles.clone')->name('clone');
        Route::get('/{role}/permissions', [RoleController::class, 'managePermissions'])->middleware('permission:roles.permissions')->name('permissions');
        Route::post('/{role}/permissions/sync', [RoleController::class, 'syncPermissions'])->middleware('permission:roles.read')->name('permissions.sync');
        Route::post('/{role}/assign-user', [RoleController::class, 'assignToUser'])->middleware('permission:roles.assign')->name('assign.user');
        Route::post('/{role}/remove-user', [RoleController::class, 'removeFromUser'])->middleware('permission:roles.read')->name('remove.user');
    });
});

