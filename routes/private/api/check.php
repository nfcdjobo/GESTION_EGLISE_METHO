<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Api\RoleApiController;
use App\Http\Controllers\Private\Api\PermissionApiController;


Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {

    // API Vérification des permissions
    Route::prefix('check')->group(function () {
        Route::post('/permission', [PermissionApiController::class, 'checkPermission']);
        Route::post('/permissions', [PermissionApiController::class, 'checkPermissions']);
        Route::post('/role', [RoleApiController::class, 'checkRole']);
        Route::post('/roles', [RoleApiController::class, 'checkRoles']);
        Route::post('/can-manage-user', [PermissionApiController::class, 'canManageUser']);
    });

});


