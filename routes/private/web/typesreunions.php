<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Web\TypeReunionController;
use App\Http\Controllers\Private\Web\ReunionController;
use App\Http\Controllers\Private\Web\RapportReunionController;

/*
|--------------------------------------------------------------------------
| Routes des Types de Réunions
|--------------------------------------------------------------------------
|
| Toutes les routes pour la gestion des types de réunions configurables
| Organisées dans un groupe avec middleware d'authentification
|
*/

Route::prefix('dashboard/types-reunions')->name('private.types-reunions.')->middleware(['auth', 'verified', 'user.status'])->group(function () {

    // Routes CRUD principales
    Route::get('/', [TypeReunionController::class, 'index'])->name('index');
    Route::get('/create', [TypeReunionController::class, 'create'])->name('create');
    Route::post('/', [TypeReunionController::class, 'store'])->name('store');

    // Route avec cache pour les statistiques
    Route::get('/statistiques/cached', [TypeReunionController::class, 'statistiquesUtilisation'])->middleware('cache.headers:public;max_age=3600')->name('statistiques.cached');

    // Pages spécialisées
    Route::get('/statistiques', [TypeReunionController::class, 'statistiquesUtilisation'])->name('statistiques');
    Route::get('/categories', [TypeReunionController::class, 'categoriesDisponibles'])->name('categories');
    Route::get('/options', [TypeReunionController::class, 'options'])->name('options');

    // Routes avec paramètres de type de réunion
    Route::get('/{typeReunion}', [TypeReunionController::class, 'show'])->name('show');
    Route::get('/{typeReunion}/edit', [TypeReunionController::class, 'edit'])->name('edit');
    Route::put('/{typeReunion}', [TypeReunionController::class, 'update'])->name('update');
    Route::delete('/{typeReunion}', [TypeReunionController::class, 'destroy'])->name('destroy');

    // Route avec validation UUID stricte
    Route::get('/{typeReunion}/strict', [TypeReunionController::class, 'show'])->where('typeReunion', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}')->name('show.strict');

    // Gestion du cycle de vie des types
    Route::post('/{typeReunion}/archiver', [TypeReunionController::class, 'archiver'])->name('archiver');
    Route::post('/{typeReunion}/restaurer', [TypeReunionController::class, 'restaurer'])->name('restaurer');
    Route::post('/{typeReunion}/dupliquer', [TypeReunionController::class, 'dupliquer'])->name('dupliquer');
    Route::post('/{typeReunion}/activer', [TypeReunionController::class, 'activer'])->name('activer');
    Route::post('/{typeReunion}/desactiver', [TypeReunionController::class, 'desactiver'])->name('desactiver');



    // Restauration d'un type supprimé
    Route::post('/{id}/restore', [TypeReunionController::class, 'restore'])->name('restore')->withTrashed();

});







// Redirections types de réunions
Route::redirect('/types-reunions', '/private/types-reunions', 301);
Route::redirect('/admin/types-reunions', '/private/types-reunions', 301);
Route::redirect('/gestion/types-reunions', '/private/types-reunions', 301);




