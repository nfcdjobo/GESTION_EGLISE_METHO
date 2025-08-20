<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Api\PermissionApiController;


Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {

    // API Permissions
    Route::prefix('permissions')->group(function () {
        Route::get('/', [PermissionApiController::class, 'index']);
        Route::get('/search', [PermissionApiController::class, 'search']);
        Route::get('/categories', [PermissionApiController::class, 'categories']);
        Route::get('/resources', [PermissionApiController::class, 'resources']);
        Route::get('/{permission}', [PermissionApiController::class, 'show']);
        Route::post('/', [PermissionApiController::class, 'store'])->middleware('permission:roles.create');
        Route::put('/{permission}', [PermissionApiController::class, 'update'])->middleware('permission:roles.update');
        Route::delete('/{permission}', [PermissionApiController::class, 'destroy'])->middleware('permission:roles.delete');
        Route::post('/{permission}/toggle', [PermissionApiController::class, 'toggle'])->middleware('permission:roles.manage');
    });

});


