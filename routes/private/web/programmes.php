<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Web\ProgrammeController;

/*
|--------------------------------------------------------------------------
| Routes pour les Programmes d'Église
|--------------------------------------------------------------------------
|
| Ces routes gèrent les programmes d'église et sont compatibles
| avec les requêtes web classiques et les appels API JavaScript
|
*/
// Routes d'administration des permissions
Route::middleware(['auth', 'user.status'])->prefix('programmes')->name('private.programmes.')->group(function () {

    Route::get('', [ProgrammeController::class, 'index'])->middleware('permission:programmes.read')->name('index');

    Route::get('/create', [ProgrammeController::class, 'create'])->middleware('permission:programmes.create')->name('create');

    Route::post('', [ProgrammeController::class, 'store'])->middleware('permission:programmes.create')->name('store');

        // Routes pour les vues spécialisées
    Route::get('/planning', [ProgrammeController::class, 'planning'])->middleware('permission:programmes.planning')->name('planning');

    Route::get('/statistiques', [ProgrammeController::class, 'statistiques'])->middleware('permission:programmes.statistics')->name('statistiques');

    // Routes API/métadonnées
    Route::get('/actifs', [ProgrammeController::class, 'actifs'])->middleware('permission:programmes.actifs')->name('actifs');

    Route::get('/metadata', [ProgrammeController::class, 'metadata'])->middleware('permission:programmes.metadata')->name('metadata');

    Route::get('/{programme}', [ProgrammeController::class, 'show'])->middleware('permission:programmes.read')->name('show');

    Route::get('/{programme}/edit', [ProgrammeController::class, 'edit'])->middleware('permission:programmes.update')->name('edit');

    Route::put('/{programme}', [ProgrammeController::class, 'update'])->middleware('permission:programmes.update')->name('update');

    Route::delete('/{programme}', [ProgrammeController::class, 'destroy'])->middleware('permission:programmes.delete')->name('destroy');

    // Routes pour la gestion des statuts
    Route::post('/{programme}/activer', [ProgrammeController::class, 'activer'])->middleware('permission:programmes.activate')->name('activer');

    Route::post('/{programme}/suspendre', [ProgrammeController::class, 'suspendre'])->middleware('permission:programmes.suspend')->name('suspendre');

    Route::post('/{programme}/terminer', [ProgrammeController::class, 'terminer'])->middleware('permission:programmes.terminate')->name('terminer');

    Route::post('/{programme}/annuler', [ProgrammeController::class, 'annuler'])->middleware('permission:programmes.cancel')->name('annuler');

    // Routes utilitaires
    Route::post('/{programme}/dupliquer', [ProgrammeController::class, 'dupliquer'])->middleware('permission:programmes.duplicate')->name('dupliquer');

});
