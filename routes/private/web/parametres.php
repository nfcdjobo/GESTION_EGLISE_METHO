<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Web\ParametresController;


Route::prefix('parametres')->name('private.parametres.')->middleware(['auth', 'verified', 'user.status'])->group(function () {
    Route::get('', [ParametresController::class, 'index'])->middleware('permission:parametres.read')->name('index');
    Route::get('/edit', [ParametresController::class, 'edit'])->middleware('permission:parametres.update')->name('edit');
    Route::put('', [ParametresController::class, 'update'])->middleware('permission:parametres.update')->name('update');
    Route::get('/show', [ParametresController::class, 'show'])->middleware('permission:parametres.read')->name('show');
    Route::post('/logo', [ParametresController::class, 'updateLogo'])->middleware('permission:parametres.update')->name('update-logo');
});
