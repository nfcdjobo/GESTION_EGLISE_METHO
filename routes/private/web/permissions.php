<?php

// routes/web.php - Ajouter ces routes

// use App\Http\Controllers\private\PermissionController;
// use App\Http\Controllers\private\RoleController;
// use App\Http\Controllers\private\UserPermissionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\private\Web\PermissionController;

// Routes d'administration des permissions
Route::middleware(['auth', 'user.status'])->prefix('permissions')->name('private.permissions.')->group(function () {

    // Gestion des permissions

    Route::get('/', [PermissionController::class, 'index'])->middleware('permission:permissions.read')->name('index');
    Route::post('/bulk-assign', [PermissionController::class, 'bulkAssign'])->middleware('permission:permissions.bulk-assign')->name('bulk-assign');

    Route::get('/statistics', [PermissionController::class, 'statistics'])->middleware('permission:permissions.statistics')->name('statistics');
    Route::get('/create', [PermissionController::class, 'create'])->middleware('permission:permissions.create')->name('create');
    Route::get('/export/csv', [PermissionController::class, 'export'])->middleware('permission:permissions.export')->name('export');

    Route::post('/', [PermissionController::class, 'store'])->middleware('permission:permissions.create')->name('store');
    Route::get('/{permission}', [PermissionController::class, 'show'])->middleware('permission:permissions.read')->name('show');
    Route::get('/{permission}/edit', [PermissionController::class, 'edit'])->middleware('permission:permissions.update')->name('edit');
    Route::put('/{permission}', [PermissionController::class, 'update'])->middleware('permission:permissions.update')->name('update');
    Route::delete('/{permission}', [PermissionController::class, 'destroy'])->middleware('permission:permissions.delete')->name('destroy');
    Route::post('/{permission}/clone', [PermissionController::class, 'clone'])->middleware('permission:permissions.clone')->name('clone');
    Route::post('/{permission}/toggle', [PermissionController::class, 'toggle'])->middleware('permission:permissions.toggle')->name('toggle');

});









