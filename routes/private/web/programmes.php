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
Route::middleware(['auth', 'user.status'])->prefix('dashboard')->name('private.programmes.')->group(function () {

    Route::get('programmes', [ProgrammeController::class, 'index'])->name('index');

    Route::get('programmes/create', [ProgrammeController::class, 'create'])->name('create');

    Route::post('programmes', [ProgrammeController::class, 'store'])->name('store');

        // Routes pour les vues spécialisées
    Route::get('programmes-planning', [ProgrammeController::class, 'planning'])->name('planning');

    Route::get('programmes-statistiques', [ProgrammeController::class, 'statistiques'])->name('statistiques');

    // Routes API/métadonnées
    Route::get('programmes-actifs', [ProgrammeController::class, 'actifs'])->name('actifs');

    Route::get('programmes-metadata', [ProgrammeController::class, 'metadata'])->name('metadata');

    Route::get('programmes/{programme}', [ProgrammeController::class, 'show'])->name('show');

    Route::get('programmes/{programme}/edit', [ProgrammeController::class, 'edit'])->name('edit');

    Route::put('programmes/{programme}', [ProgrammeController::class, 'update'])->name('update');

    Route::delete('programmes/{programme}', [ProgrammeController::class, 'destroy'])->name('destroy');

    // Routes pour la gestion des statuts
    Route::post('programmes/{programme}/activer', [ProgrammeController::class, 'activer'])->name('activer');

    Route::post('programmes/{programme}/suspendre', [ProgrammeController::class, 'suspendre'])->name('suspendre');

    Route::post('programmes/{programme}/terminer', [ProgrammeController::class, 'terminer'])->name('terminer');

    Route::post('programmes/{programme}/annuler', [ProgrammeController::class, 'annuler'])->name('annuler');

    // Routes utilitaires
    Route::post('programmes/{programme}/dupliquer', [ProgrammeController::class, 'dupliquer'])->name('dupliquer');

});
