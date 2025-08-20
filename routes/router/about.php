<?php

use App\Http\Controllers\Public\HistoriqueController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix('about')->name('public.about')->group(function(){
    Route::get('', [HistoriqueController::class, 'index']);
});
