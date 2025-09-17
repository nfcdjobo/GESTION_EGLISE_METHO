<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\private\Web\RoleController;

// Routes d'administration des permissions
Route::middleware(['auth', 'user.status'])->prefix('dashboard')->name('private.')->group(function () {

    // Gestion des rôles
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::get('/hierarchy', [RoleController::class, 'hierarchy'])->name('hierarchy');
        Route::post('/compare', [RoleController::class, 'compare'])->name('compare');
        Route::get('/export/csv', [RoleController::class, 'export'])->name('export');
        Route::get('/{role}', [RoleController::class, 'show'])->name('show');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
        Route::put('/{role}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
        Route::post('/{role}/clone', [RoleController::class, 'clone'])->name('clone');
        Route::get('/{role}/permissions', [RoleController::class, 'managePermissions'])->name('permissions');
        Route::post('/{role}/permissions/sync', [RoleController::class, 'syncPermissions'])->name('permissions.sync');
        Route::post('/{role}/assign-user', [RoleController::class, 'assignToUser'])->name('assign.user');
        Route::post('/{role}/remove-user', [RoleController::class, 'removeFromUser'])->name('remove.user');
    });


});

