<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Web\AnnonceController;

// Routes protégées par authentification
Route::middleware(['auth', 'user.status'])->prefix('annonces')->name('private.annonces.')->group(function () {

    // Routes CRUD standard pour les annonces
    Route::get('', [AnnonceController::class, 'index'])->middleware('permission:annonces.read')->name('index');
    Route::get('/create', [AnnonceController::class, 'create'])->middleware('permission:annonces.create')->name('create');
    // Annonces pour le culte
    Route::get('/culte', [AnnonceController::class, 'pourCulte'])->middleware('permission:annonces.culte')->name('culte');

    // Annonces urgentes
    Route::get('/urgentes', [AnnonceController::class, 'urgentes'])->name('urgentes');

    // Statistiques (pour dashboard)
    Route::get('/statistiques', [AnnonceController::class, 'statistiques'])->name('statistiques');

    // Annonces actives (API publique)
    Route::get('/actives', [AnnonceController::class, 'annoncesActives'])->middleware('permission:annonces.actives')->name('actives');

    Route::get('/export-liste-pdf', [AnnonceController::class, 'exportListePdf'])->name('export-liste-pdf')->middleware('permission:annonces.read');





    Route::post('', [AnnonceController::class, 'store'])->middleware('permission:annonces.create')->name('store');
    Route::get('/{annonce}', [AnnonceController::class, 'show'])->middleware('permission:annonces.read')->name('show');

    Route::put('/{annonce}', [AnnonceController::class, 'update'])->middleware('permission:annonces.update')->name('update');
    Route::delete('/{annonce}', [AnnonceController::class, 'destroy'])->middleware('permission:annonces.delete')->name('destroy');

        // Routes pour l'export PDF des annonces
    Route::get('/{annonce}/export-pdf', [AnnonceController::class, 'exportPdf'])->name('export-pdf')->middleware('permission:annonces.read');

    Route::get('/{annonce}/edit', [AnnonceController::class, 'edit'])->middleware('permission:annonces.update')->name('edit');
    // Actions sur une annonce spécifique
    Route::put('{annonce}/publier', [AnnonceController::class, 'publier'])->middleware('permission:annonces.publish')->name('publier');
    Route::put('{annonce}/archiver', [AnnonceController::class, 'archiver'])->middleware('permission:annonces.archive')->name('archiver');
    Route::post('{annonce}/dupliquer', [AnnonceController::class, 'dupliquer'])->middleware('permission:annonces.duplicate')->name('dupliquer');

});


