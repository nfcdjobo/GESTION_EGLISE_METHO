<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\DonController;




 Route::prefix('donates')->name('public.donates.')->group(function () {
    Route::get('', [DonController::class, 'index'])->name('index');
    Route::get('{parametreDon}/strict-adding-preuves', [DonController::class, 'create'])->name('create');

    Route::post('/strict-adding-preuves', [DonController::class, 'store'])->name('store');

});
