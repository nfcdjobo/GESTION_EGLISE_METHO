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

Route::prefix('types-reunions')->name('private.types-reunions.')->middleware(['auth', 'verified', 'user.status'])->group(function () {

    // Routes CRUD principales
    Route::get('/', [TypeReunionController::class, 'index'])->middleware('permission:types-reunions.read')->name('index');
    Route::get('/create', [TypeReunionController::class, 'create'])->middleware('permission:types-reunions.read')->name('create');
    Route::post('/', [TypeReunionController::class, 'store'])->middleware('permission:types-reunions.read')->name('store');

    // Route avec cache pour les statistiques
    Route::get('/statistiques/cached', [TypeReunionController::class, 'statistiquesUtilisation'])->middleware('cache.headers:public;max_age=3600')->middleware('permission:types-reunions.read')->name('statistiques.cached');

    // Pages spécialisées
    Route::get('/statistiques', [TypeReunionController::class, 'statistiquesUtilisation'])->middleware('permission:types-reunions.read')->name('statistiques');
    Route::get('/categories', [TypeReunionController::class, 'categoriesDisponibles'])->middleware('permission:types-reunions.read')->name('categories');
    Route::get('/options', [TypeReunionController::class, 'options'])->middleware('permission:types-reunions.read')->name('options');

    // Routes avec paramètres de type de réunion
    Route::get('/{typeReunion}', [TypeReunionController::class, 'show'])->middleware('permission:types-reunions.read')->name('show');
    Route::get('/{typeReunion}/edit', [TypeReunionController::class, 'edit'])->middleware('permission:types-reunions.read')->name('edit');
    Route::put('/{typeReunion}', [TypeReunionController::class, 'update'])->middleware('permission:types-reunions.read')->name('update');
    Route::delete('/{typeReunion}', [TypeReunionController::class, 'destroy'])->middleware('permission:types-reunions.read')->name('destroy');

    // Route avec validation UUID stricte
    Route::get('/{typeReunion}/strict', [TypeReunionController::class, 'show'])->where('typeReunion', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}')->middleware('permission:types-reunions.read')->name('show.strict');

    // Gestion du cycle de vie des types
    Route::post('/{typeReunion}/archiver', [TypeReunionController::class, 'archiver'])->middleware('permission:types-reunions.read')->name('archiver');
    Route::post('/{typeReunion}/restaurer', [TypeReunionController::class, 'restaurer'])->middleware('permission:types-reunions.read')->name('restaurer');
    Route::post('/{typeReunion}/dupliquer', [TypeReunionController::class, 'dupliquer'])->middleware('permission:types-reunions.read')->name('dupliquer');
    Route::post('/{typeReunion}/activer', [TypeReunionController::class, 'activer'])->middleware('permission:types-reunions.read')->name('activer');
    Route::post('/{typeReunion}/desactiver', [TypeReunionController::class, 'desactiver'])->middleware('permission:types-reunions.read')->name('desactiver');



    // Restauration d'un type supprimé
    Route::post('/{id}/restore', [TypeReunionController::class, 'restore'])->middleware('permission:types-reunions.read')->name('restore')->withTrashed();

});







