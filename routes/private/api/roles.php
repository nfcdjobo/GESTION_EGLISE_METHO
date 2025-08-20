<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Api\RoleApiController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {

    // API Rôles
    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleApiController::class, 'index']);
        Route::get('/search', [RoleApiController::class, 'search']);
        Route::get('/hierarchy', [RoleApiController::class, 'hierarchy']);
        Route::get('/{role}', [RoleApiController::class, 'show']);
        Route::get('/{role}/permissions', [RoleApiController::class, 'permissions']);
        Route::get('/{role}/users', [RoleApiController::class, 'users']);
        Route::post('/', [RoleApiController::class, 'store'])->middleware('permission:roles.create');
        Route::put('/{role}', [RoleApiController::class, 'update'])->middleware('permission:roles.update');
        Route::delete('/{role}', [RoleApiController::class, 'destroy'])->middleware('permission:roles.delete');
        Route::post('/{role}/permissions/sync', [RoleApiController::class, 'syncPermissions'])->middleware('permission:roles.manage');
        Route::post('/{role}/users/{user}/assign', [RoleApiController::class, 'assignToUser'])->middleware('permission:roles.assign');
        Route::delete('/{role}/users/{user}/remove', [RoleApiController::class, 'removeFromUser'])->middleware('permission:roles.assign');
    });

});


