<?php

// routes/web.php - Ajouter ces routes

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\UserPermissionController;


// Routes pour le profil membres
Route::middleware(['auth'])->prefix('profile')->name('profile.')->group(function () {
    Route::get('/permissions', [UserPermissionController::class, 'myPermissions'])->name('permissions');
    Route::get('/roles', [UserPermissionController::class, 'myRoles'])->name('roles');
    Route::get('/permissions/expiring', [UserPermissionController::class, 'myExpiringPermissions'])->name('permissions.expiring');
});
