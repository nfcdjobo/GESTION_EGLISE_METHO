<?php

use App\Http\Controllers\Private\Web\ParametreDonController;
use Illuminate\Support\Facades\Route;



Route::prefix('parametredons')->name('private.parametresdons.')->middleware(['auth', 'verified', 'user.status'])->group(function () {
    Route::get('/', [ParametreDonController::class, 'index'])->name('index');

        Route::get('/create', [ParametreDonController::class, 'create'])->middleware('permission:parametresdons.create')->name('create');
        Route::get('/export', [ParametreDonController::class, 'export'])->middleware('permission:parametresdons.export')->name('export');
        Route::get('/statistics', [ParametreDonController::class, 'statistics'])->middleware('permission:parametresdons.statistics')->name('statistics');
        Route::get('/publics', [ParametreDonController::class, 'parametresPublics'])->middleware('permission:parametresdons.read')->name('publics');

        Route::post('/', [ParametreDonController::class, 'store'])->middleware('permission:parametresdons.create')->name('store');
        Route::get('/{parametreDon}', [ParametreDonController::class, 'show'])->middleware('permission:parametresdons.read')->name('show');
        Route::get('/{parametreDon}/edit', [ParametreDonController::class, 'edit'])->middleware('permission:parametresdons.update')->name('edit');
        Route::put('/{parametreDon}', [ParametreDonController::class, 'update'])->middleware('permission:parametresdons.update')->name('update');
        Route::delete('/{parametreDon}', [ParametreDonController::class, 'destroy'])->middleware('permission:parametresdons.delete')->name('destroy');
        Route::patch('/{parametreDon}/toggle-status', [ParametreDonController::class, 'toggleStatut'])->middleware('permission:parametresdons.toggle-status')->name('toggle-statut');
        Route::patch('/{parametreDon}/toggle-publication', [ParametreDonController::class, 'togglePublication'])->middleware('permission:parametresdons.toggle-publication')->name('toggle-publication');
});
