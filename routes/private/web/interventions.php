<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Web\InterventionController;

/*
|--------------------------------------------------------------------------
| Routes Web et Api pour les Interventions
|--------------------------------------------------------------------------
|
| Routes pour l'interface web avec vues Blade et aussi api à la fois
|
*/

Route::prefix('dashboard/interventions')->name('private.interventions.')->middleware(['auth', 'verified', 'user.status'])->group(function () {

    // Routes de base CRUD
    Route::get('/', [InterventionController::class, 'index'])->name('index');
    Route::get('create', [InterventionController::class, 'create'])->name('create');
    Route::post('/', [InterventionController::class, 'store'])->name('store');
    Route::get('{intervention}', [InterventionController::class, 'show'])->name('show');
    Route::get('{intervention}/edit', [InterventionController::class, 'edit'])->name('edit');
    Route::put('{intervention}', [InterventionController::class, 'update'])->name('update');
    Route::patch('{intervention}', [InterventionController::class, 'update'])->name('update.patch');
    Route::delete('{intervention}', [InterventionController::class, 'destroy'])->name('destroy');

    // Routes spécifiques
    Route::get('trash/list', [InterventionController::class, 'trash'])->name('trash');
    Route::patch('{intervention}/restore', [InterventionController::class, 'restore'])->name('restore');
    Route::patch('{intervention}/statut', [InterventionController::class, 'changeStatut'])->name('change-statut');
    Route::get('evenement/list', [InterventionController::class, 'parEvenement'])->name('par-evenement');

});





Route::pattern('intervention', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');


