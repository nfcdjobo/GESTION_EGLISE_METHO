<?php

use App\Http\Controllers\Private\Web\DonController;
use App\Http\Controllers\Private\Web\ParametreDonController;
use Illuminate\Support\Facades\Route;

Route::prefix('donation')->name('private.dons.')->middleware(['auth', 'verified', 'user.status'])->group(function () {

    // ================================
    // ROUTES DE LECTURE
    // ================================
    Route::get('/', [DonController::class, 'index'])->middleware('permission:donation.read')->name('index');


    // ================================
    // ROUTES DE CRÉATION
    // ================================
    Route::get('/create', [DonController::class, 'create'])->middleware('permission:donation.create')->name('create');

    // Export
    Route::get('/export', [DonController::class, 'export'])->middleware('permission:donation.export')->name('export');

    // Statistiques et Dashboard
    Route::get('/statistiques', [DonController::class, 'statistiques'])->middleware('permission:donation.statistics')->name('statistiques');
    Route::get('/dashboard', [DonController::class, 'dashboard'])->middleware('permission:donation.dashboard')->name('dashboard');
    Route::get('/statistiques-publiques', [DonController::class, 'statistiquesPubliques'])->middleware('permission:donation.statistics-publiques')->name('statistiquesPubliques');

    // Rapports et recherches
    Route::get('/recherche-avancee', [DonController::class, 'rechercheAvancee'])->middleware('permission:donation.recherche-avancee')->name('rechercheAvancee');
    Route::get('/par-donateur', [DonController::class, 'parDonateur'])->middleware('permission:donation.par-donateur')->name('parDonateur');
    Route::get('/rapport-personnalise', [DonController::class, 'rapportPersonnalise'])->middleware('permission:donation.rapport-personnalise')->name('rapportPersonnalise');


    Route::post('/', [DonController::class, 'store'])->middleware('permission:donation.create')->name('store');



    Route::get('/{don}', [DonController::class, 'show'])->middleware('permission:donation.read')->name('show');
     Route::get('/{don}/edit', [DonController::class, 'edit'])->middleware('permission:donation.update')->name('edit');
     // Téléchargement et duplication
    Route::get('/{don}/telecharger-preuve', [DonController::class, 'telechargerPreuve'])->middleware('permission:donation.telecharger-preuve')->name('telechargerPreuve');

    Route::put('/{don}', [DonController::class, 'update'])->middleware('permission:donation.update')->name('update');

    // ================================
    // ROUTES DE SUPPRESSION
    // ================================
    Route::delete('/{don}', [DonController::class, 'destroy'])->middleware('permission:donation.delete')->name('destroy');

    // ================================
    // ROUTES FONCTIONNELLES
    // ================================

    Route::post('/{don}/dupliquer', [DonController::class, 'dupliquer'])->middleware('permission:donation.dupliquer')->name('dupliquer');
});
