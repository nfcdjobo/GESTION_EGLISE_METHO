<?php

use App\Http\Controllers\Public\EvenementController;
use App\Http\Controllers\Public\HoraireController;
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

Route::prefix('programme')->name('public.horaires')->group(function(){
    Route::get('', [HoraireController::class, 'index']);
});
