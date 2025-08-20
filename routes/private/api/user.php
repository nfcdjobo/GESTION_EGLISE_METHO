<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Api\RoleApiController;
use App\Http\Controllers\Private\Api\PermissionApiController;


Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {

    // API Permissions utilisateur
    Route::prefix('users/{user}')->group(function () {
        Route::get('/permissions', [PermissionApiController::class, 'userPermissions']);
        Route::get('/permissions/all', [PermissionApiController::class, 'allUserPermissions']);
        Route::get('/permissions/direct', [PermissionApiController::class, 'directUserPermissions']);
        Route::get('/permissions/via-roles', [PermissionApiController::class, 'permissionsViaRoles']);
        Route::post('/permissions/grant', [PermissionApiController::class, 'grantToUser'])->middleware('permission:users.manage');
        Route::post('/permissions/revoke', [PermissionApiController::class, 'revokeFromUser'])->middleware('permission:users.manage');
        Route::get('/roles', [RoleApiController::class, 'userRoles']);
        Route::post('/roles/assign', [RoleApiController::class, 'assignRoleToUser'])->middleware('permission:roles.assign');
        Route::post('/roles/remove', [RoleApiController::class, 'removeRoleFromUser'])->middleware('permission:roles.assign');
        Route::post('/roles/sync', [RoleApiController::class, 'syncUserRoles'])->middleware('permission:roles.manage');
        Route::get('/audit', [PermissionApiController::class, 'auditUser'])->middleware('permission:users.manage');
    });

});


