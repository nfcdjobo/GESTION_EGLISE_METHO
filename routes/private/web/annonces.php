<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Web\AnnonceController;

// Routes protégées par authentification
Route::middleware(['auth', 'user.status'])->prefix('dashboard/annonces')->name('private.annonces.')->group(function () {

    // Routes CRUD standard pour les annonces
    Route::get('', [AnnonceController::class, 'index'])->name('index');
    Route::get('/create', [AnnonceController::class, 'create'])->name('create');
    // Annonces pour le culte
    Route::get('/culte', [AnnonceController::class, 'pourCulte'])->name('culte');

    // Annonces urgentes
    Route::get('/urgentes', [AnnonceController::class, 'urgentes'])->name('urgentes');

    // Statistiques (pour dashboard)
    Route::get('/statistiques', [AnnonceController::class, 'statistiques'])->name('statistiques');

    // Annonces actives (API publique)
    Route::get('/actives', [AnnonceController::class, 'annoncesActives'])->name('actives');

    Route::post('', [AnnonceController::class, 'store'])->name('store');
    Route::get('/{annonce}', [AnnonceController::class, 'show'])->name('show');

    Route::put('/{annonce}', [AnnonceController::class, 'update'])->name('update');
    Route::delete('/{annonce}', [AnnonceController::class, 'destroy'])->name('destroy');

    Route::get('/{annonce}/edit', [AnnonceController::class, 'edit'])->name('edit');
    // Actions sur une annonce spécifique
    Route::put('{annonce}/publier', [AnnonceController::class, 'publier'])->name('publier');
    Route::put('{annonce}/archiver', [AnnonceController::class, 'archiver'])->name('archiver');
    Route::post('{annonce}/dupliquer', [AnnonceController::class, 'dupliquer'])->name('dupliquer');

});


