<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Web\FondsController;

/*
|--------------------------------------------------------------------------
| Routes des Fonds
|--------------------------------------------------------------------------
|
| Toutes les routes pour la gestion des transactions financières
| Organisées dans un groupe avec middleware d'authentification
|
*/

Route::prefix('dashboard/fonds')->name('private.fonds.')->middleware(['auth', 'verified', 'user.status'])->group(function () {

    Route::get('/', [FondsController::class, 'index'])->name('index');

    Route::get('/create', [FondsController::class, 'create'])->name('create');

    Route::post('/', [FondsController::class, 'store'])->name('store');


    // Pages spécialisées
    Route::get('/dashboard', [FondsController::class, 'dashboard'])->name('dashboard');

    Route::get('/statistics', [FondsController::class, 'statistics'])->name('statistics');

    Route::get('/analytics', [FondsController::class, 'analytics'])->name('analytics');

    Route::get('/reports', [FondsController::class, 'reports'])->name('reports');

    // Route avec cache pour les statistiques
    Route::get('/statistics/cached', [FondsController::class, 'statistics'])->middleware('cache.headers:public;max_age=3600')->name('statistics.cached');

    Route::get('/{fonds}', [FondsController::class, 'show'])->name('show');

    Route::get('/{fonds}/edit', [FondsController::class, 'edit'])->name('edit');

    Route::put('/{fonds}', [FondsController::class, 'update'])->name('update');

    Route::delete('/{fonds}', [FondsController::class, 'destroy'])->name('destroy');

    // Route avec validation UUID stricte
    Route::get('/{fonds}/strict', [FondsController::class, 'show'])->where('fonds', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}')->name('show.strict');

    // Gestion du statut des transactions
    Route::patch('/{fonds}/validate', [FondsController::class, 'validateTransaction'])->name('validate');

    Route::patch('/{fonds}/cancel', [FondsController::class, 'cancel'])->name('cancel');

    Route::patch('/{fonds}/refund', [FondsController::class, 'refund'])->name('refund');

    // Génération de reçu fiscal
    Route::post('/{fonds}/receipt', [FondsController::class, 'generateReceipt'])->name('receipt');

    // Duplication de transaction
    Route::post('/{fonds}/duplicate', [FondsController::class, 'duplicate'])->name('duplicate');

    // Restauration d'une transaction supprimée
    Route::patch('/{id}/restore', [FondsController::class, 'restore'])->name('restore')->withTrashed();

    // Export des données
    Route::get('/export/data', [FondsController::class, 'export'])->name('export');

    // Routes avec validation UUID pour les actions sensibles
    Route::patch('/{fonds}/validate', [FondsController::class, 'validateTransaction'])
        ->name('validate.strict')
        ->where('fonds', '[0-9a-f-]{36}');

    Route::patch('/{fonds}/cancel', [FondsController::class, 'cancel'])
        ->name('cancel.strict')
        ->where('fonds', '[0-9a-f-]{36}');

    Route::post('/{fonds}/receipt', [FondsController::class, 'generateReceipt'])->name('receipt.strict')->where('fonds', '[0-9a-f-]{36}');

    // Dans routes/web.php
    // Route::get('/{fonds}/receipt/form', [FondsController::class, 'showReceiptForm'])
    //      ->name('receipt.form');
    // Route::get('/{fonds}/receipt', [FondsController::class, 'generateReceipt'])
    //      ->name('receipt.strict');
    // Route::get('/{fonds}/receipt/preview', [FondsController::class, 'previewReceipt'])
    //      ->name('receipt.preview');
    // Route::post('/{fonds}/receipt/email', [FondsController::class, 'sendReceiptByEmail'])
    //      ->name('receipt.email');


    Route::get('/{fonds}/receipt/form', [FondsController::class, 'showReceiptForm'])
         ->name('receipt.form');
    Route::get('/{fonds}/receipt/download', [FondsController::class, 'generateReceipt'])
         ->name('receipt.download');
    Route::get('/{fonds}/receipt/preview', [FondsController::class, 'previewReceipt'])
         ->name('receipt.preview');
    Route::post('/{fonds}/receipt/email', [FondsController::class, 'sendReceiptByEmail'])
         ->name('receipt.email');


});



// Redirection des anciennes URLs
Route::redirect('/fonds', '/private/fonds', 301);
Route::redirect('/admin/fonds', '/private/fonds', 301);
Route::redirect('/finances', '/private/fonds', 301);
Route::redirect('/transactions', '/private/fonds', 301);
