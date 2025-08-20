<?php

use App\Http\Controllers\Public\CulteController;
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

Route::prefix('cultes')->name('public.culte')->group(function(){
    Route::get('', [CulteController::class, 'index']);
});
