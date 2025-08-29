<?php

// routes/web.php - Ajouter ces routes

// use App\Http\Controllers\private\PermissionController;
// use App\Http\Controllers\private\RoleController;
// use App\Http\Controllers\private\UserPermissionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\private\Web\PermissionController;

// Routes d'administration des permissions
Route::middleware(['auth', 'user.status'])->prefix('dashboard')->name('private.')->group(function () {

    // Gestion des permissions
    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::post('/bulk-assign', [PermissionController::class, 'bulkAssign'])->name('bulk-assign');

        Route::get('/statistics', [PermissionController::class, 'statistics'])->name('statistics');
        Route::get('/create', [PermissionController::class, 'create'])->name('create');
        Route::get('/export/csv', [PermissionController::class, 'export'])->name('export');

        Route::post('/', [PermissionController::class, 'store'])->name('store');
        Route::get('/{permission}', [PermissionController::class, 'show'])->name('show');
        Route::get('/{permission}/edit', [PermissionController::class, 'edit'])->name('edit');
        Route::put('/{permission}', [PermissionController::class, 'update'])->name('update');
        Route::delete('/{permission}', [PermissionController::class, 'destroy'])->name('destroy');
        Route::post('/{permission}/clone', [PermissionController::class, 'clone'])->name('clone');
        Route::post('/{permission}/toggle', [PermissionController::class, 'toggle'])->name('toggle');

    });




});









