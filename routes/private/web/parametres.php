<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Web\ParametresController;


Route::prefix('parametres')->name('private.parametres.')->middleware(['auth', 'verified', 'user.status'])->group(function () {
    Route::get('', [ParametresController::class, 'index'])->middleware('permission:parametres.read')->name('index');
    Route::get('/edit', [ParametresController::class, 'edit'])->middleware('permission:parametres.update')->name('edit');
    Route::put('', [ParametresController::class, 'update'])->middleware('permission:parametres.update')->name('update');
    Route::get('/show', [ParametresController::class, 'show'])->middleware('permission:parametres.read')->name('show');
    Route::post('/logo', [ParametresController::class, 'updateLogo'])->middleware('permission:parametres.update')->name('update-logo');


    // ============= ROUTES IMAGES HERO =============
    Route::prefix('images-hero')->name('images-hero.')->group(function () {
        // Récupérer toutes les images hero
        Route::get('/', [ParametresController::class, 'getImagesHero'])->name('index');

        // Récupérer une image hero spécifique
        Route::get('/{id}', [ParametresController::class, 'getImageHero'])->name('show');

        // Ajouter une nouvelle image hero
        Route::post('/', [ParametresController::class, 'ajouterImageHero'])->name('store');

        // Mettre à jour une image hero
        Route::put('/{id}', [ParametresController::class, 'mettreAJourImageHero'])->name('update');
        Route::post('/{id}', [ParametresController::class, 'mettreAJourImageHero'])->name('update.post'); // Pour formulaires avec fichiers

        // Supprimer une image hero
        Route::delete('/{id}', [ParametresController::class, 'supprimerImageHero'])->name('destroy');

        // Réorganiser les images hero
        Route::post('/reordonner', [ParametresController::class, 'reordonnerImagesHero'])->name('reorder');
    });
});
