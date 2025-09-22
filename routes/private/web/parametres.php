<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Web\ParametresController;


Route::prefix('dashboard/parametres')->name('private.parametres.')->middleware(['auth', 'verified', 'user.status'])->group(function () {
    Route::get('', [ParametresController::class, 'index'])->name('index');
    Route::get('/edit', [ParametresController::class, 'edit'])->name('edit');
    Route::put('', [ParametresController::class, 'update'])->name('update');
    Route::get('/show', [ParametresController::class, 'show'])->name('show');
    Route::post('/logo', [ParametresController::class, 'updateLogo'])->name('update-logo');
});
