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

Route::prefix('interventions')->name('private.interventions.')->middleware(['auth', 'verified', 'user.status'])->group(function () {

    // Routes de base CRUD
    Route::get('/', [InterventionController::class, 'index'])->middleware('permission:interventions.read')->name('index');
    Route::get('create', [InterventionController::class, 'create'])->middleware('permission:interventions.create')->name('create');
    Route::post('/', [InterventionController::class, 'store'])->middleware('permission:interventions.create')->name('store');
    Route::get('{intervention}', [InterventionController::class, 'show'])->middleware('permission:interventions.read')->name('show');
    Route::get('{intervention}/edit', [InterventionController::class, 'edit'])->middleware('permission:interventions.update')->name('edit');
    Route::put('{intervention}', [InterventionController::class, 'update'])->middleware('permission:interventions.update')->name('update');
    Route::patch('{intervention}', [InterventionController::class, 'update'])->middleware('permission:interventions.update')->name('update.patch');
    Route::delete('{intervention}', [InterventionController::class, 'destroy'])->middleware('permission:interventions.delete')->name('destroy');

    // Routes spécifiques
    Route::get('trash/list', [InterventionController::class, 'trash'])->middleware('permission:interventions.read')->name('trash');
    Route::get('evenement/list', [InterventionController::class, 'parEvenement'])->middleware('permission:interventions.read')->name('par-evenement');
    Route::patch('{intervention}/restore', [InterventionController::class, 'restore'])->middleware('permission:interventions.restore')->name('restore');
    Route::patch('{intervention}/statut', [InterventionController::class, 'changeStatut'])->middleware('permission:interventions.toggle-statut')->name('change-statut');


});





Route::pattern('intervention', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');


