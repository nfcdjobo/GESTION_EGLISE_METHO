<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Web\ProjetController;

/*
|--------------------------------------------------------------------------
| Routes des Projets
|--------------------------------------------------------------------------
|
| Toutes les routes pour la gestion des projets de l'église
| Organisées dans un groupe avec middleware d'authentification
|
*/

Route::prefix('private/projets')->name('private.projets.')->middleware(['auth', 'verified', 'user.status'])->group(function () {

    // Routes CRUD principales
    Route::get('/', [ProjetController::class, 'index'])->name('index');

    Route::get('/create', [ProjetController::class, 'create'])->name('create');

    Route::post('/', [ProjetController::class, 'store'])->name('store');

    // Route avec cache pour les statistiques
    Route::get('/statistiques/cached', [ProjetController::class, 'statistiques'])->middleware('cache.headers:public;max_age=3600')->name('statistiques.cached');

    // Pages spécialisées
    Route::get('/statistiques', [ProjetController::class, 'statistiques'])->name('statistiques');

    Route::get('/publics', [ProjetController::class, 'projetsPublics'])->name('publics');

    Route::get('/options', [ProjetController::class, 'options'])->name('options');

    // Routes avec paramètres de projet
    Route::get('/{projet}', [ProjetController::class, 'show'])->name('show');

    Route::get('/{projet}/edit', [ProjetController::class, 'edit'])->name('edit');

    Route::put('/{projet}', [ProjetController::class, 'update'])->name('update');

    Route::delete('/{projet}', [ProjetController::class, 'destroy'])->name('destroy');

    // Route avec validation UUID stricte
    Route::get('/{projet}/strict', [ProjetController::class, 'show'])->where('projet', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}')->name('show.strict');

    // Gestion du cycle de vie des projets
    Route::post('/{projet}/approuver', [ProjetController::class, 'approuver'])->name('approuver');

    Route::post('/{projet}/planifier', [ProjetController::class, 'planifier'])->name('planifier');

    Route::post('/{projet}/rechercher-financement', [ProjetController::class, 'rechercherFinancement'])->name('rechercher-financement'); // NOUVELLE LIGNE

    Route::post('/{projet}/demarrer', [ProjetController::class, 'demarrer'])->name('demarrer');

    Route::post('/{projet}/suspendre', [ProjetController::class, 'suspendre'])->name('suspendre');

    Route::post('/{projet}/reprendre', [ProjetController::class, 'reprendre'])->name('reprendre');

    Route::post('/{projet}/terminer', [ProjetController::class, 'terminer'])->name('terminer');

    Route::post('/{projet}/annuler', [ProjetController::class, 'annuler'])->name('annuler');

    // Mise à jour de la progression
    Route::post('/{projet}/progression', [ProjetController::class, 'mettreAJourProgression'])->name('progression');
    Route::post('/{projet}/mettre-en-attente', [ProjetController::class, 'mettreEnAttente'])->name('mettre-en-attente');

    // Duplication de projet
    Route::post('/{projet}/dupliquer', [ProjetController::class, 'dupliquer'])->name('dupliquer');

    // Upload d'images
    Route::post('/{projet}/upload-image', [ProjetController::class, 'uploadImage'])->name('upload.image');

    // Restauration d'un projet supprimé
    Route::post('/{id}/restore', [ProjetController::class, 'restore'])->name('restore')->withTrashed();

});






// Redirection des anciennes URLs
Route::redirect('/projets', '/private/projets', 301);
Route::redirect('/admin/projets', '/private/projets', 301);
Route::redirect('/gestion/projets', '/private/projets', 301);
