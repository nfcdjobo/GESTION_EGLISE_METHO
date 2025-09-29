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

Route::prefix('fonds')->name('private.fonds.')->middleware(['auth', 'verified', 'user.status'])->group(function () {

    Route::get('/', [FondsController::class, 'index'])->middleware('permission:fonds.read')->name('index');

    Route::get('/create', [FondsController::class, 'create'])->middleware('permission:fonds.create')->name('create');

    Route::post('/', [FondsController::class, 'store'])->middleware('permission:fonds.create')->name('store');


    // Pages spécialisées
    Route::get('/dashboard', [FondsController::class, 'dashboard'])->middleware('permission:fonds.dashboard')->name('dashboard');

    Route::get('/statistics', [FondsController::class, 'statistics'])->middleware('permission:fonds.statistics')->name('statistics');

    Route::get('/analytics', [FondsController::class, 'analytics'])->middleware('permission:fonds.analytics')->name('analytics');

    Route::get('/reports', [FondsController::class, 'reports'])->middleware('permission:fonds.reports')->name('reports');

    // Export des données
    Route::get('/export/data', [FondsController::class, 'export'])->middleware('permission:fonds.export')->name('export');

    // Route avec cache pour les statistiques
    Route::get('/statistics/cached', [FondsController::class, 'statistics'])->middleware('permission:fonds.statistics')->middleware('cache.headers:public;max_age=3600')->name('statistics.cached');

    Route::get('/{fonds}', [FondsController::class, 'show'])->middleware('permission:fonds.read')->name('show');

    Route::get('/{fonds}/edit', [FondsController::class, 'edit'])->middleware('permission:fonds.update')->name('edit');

    Route::put('/{fonds}', [FondsController::class, 'update'])->middleware('permission:fonds.update')->name('update');

    Route::delete('/{fonds}', [FondsController::class, 'destroy'])->middleware('permission:fonds.delete')->name('destroy');

    // Route avec validation UUID stricte
    Route::get('/{fonds}/strict', [FondsController::class, 'show'])->where('fonds', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}')->name('show.strict');

    // Gestion du statut des transactions
    Route::patch('/{fonds}/validate', [FondsController::class, 'validateTransaction'])->middleware('permission:fonds.validate')->name('validate');

    Route::patch('/{fonds}/cancel', [FondsController::class, 'cancel'])->middleware('permission:fonds.cancel')->name('cancel');

    Route::patch('/{fonds}/refund', [FondsController::class, 'refund'])->middleware('permission:fonds.refund')->name('refund');

    // Génération de reçu fiscal
    Route::post('/{fonds}/receipt', [FondsController::class, 'generateReceipt'])->middleware('permission:fonds.generate-receipt')->name('receipt');

    // Duplication de transaction
    Route::post('/{fonds}/duplicate', [FondsController::class, 'duplicate'])->middleware('permission:fonds.duplicate')->name('duplicate');

    // Restauration d'une transaction supprimée
    Route::patch('/{fonds}/restore', [FondsController::class, 'restore'])->middleware('permission:fonds.restore')->name('restore')->withTrashed();

    // Routes avec validation UUID pour les actions sensibles
    Route::patch('/{fonds}/validate', [FondsController::class, 'validateTransaction'])->middleware('permission:fonds.validate')->name('validate.strict')->where('fonds', '[0-9a-f-]{36}');

    Route::patch('/{fonds}/cancel', [FondsController::class, 'cancel'])->middleware('permission:fonds.cancel')->name('cancel.strict')->where('fonds', '[0-9a-f-]{36}');

    Route::post('/{fonds}/receipt', [FondsController::class, 'generateReceipt'])->middleware('permission:fonds.generate-receipt')->name('receipt.strict')->where('fonds', '[0-9a-f-]{36}');




    Route::get('/{fonds}/receipt/form', [FondsController::class, 'showReceiptForm'])->middleware('permission:fonds.receipt-form')->name('receipt.form');
    Route::get('/{fonds}/receipt/download', [FondsController::class, 'generateReceipt'])->middleware('permission:fonds.receipt-download')->name('receipt.download');
    Route::get('/{fonds}/receipt/preview', [FondsController::class, 'previewReceipt'])->middleware('permission:fonds.receipt-preview')->name('receipt.preview');
    Route::post('/{fonds}/receipt/email', [FondsController::class, 'sendReceiptByEmail'])->middleware('permission:fonds.receipt-email')->name('receipt.email');


});




