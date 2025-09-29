<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\WelcomeController;

Route::get('/', [WelcomeController::class, 'index'])->name('public.accueil');
