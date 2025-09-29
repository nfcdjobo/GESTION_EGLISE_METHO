<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Web\AlerteController;
use App\Http\Controllers\Private\Web\AnnonceController;

// Routes protégées par authentification
Route::middleware(['auth', 'user.status'])->prefix('alertes')->name('private.alertes.')->group(function () {
    // Routes CRUD standard pour les annonces
    Route::get('', [AlerteController::class, 'membresAssiduiteFaible'])->middleware('permission:alertes.read')->name('assiduite-faible');
});
