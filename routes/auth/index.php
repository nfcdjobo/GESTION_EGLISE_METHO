<?php

use App\Http\Controllers\Auth\Web\AuthenteController;
use Illuminate\Support\Facades\Route;




// Groupe de routes pour la sécurité (préfixe 'security')
Route::prefix('security')->name('security.')->group(function () {

    // Routes accessibles aux invités seulement
    Route::middleware('guest')->group(function () {
        // Affichage des formulaires
        Route::get('/login', [AuthenteController::class, 'viewlogin'])->name('login');
        Route::get('/register', [AuthenteController::class, 'viewregister'])->name('viewregister');

        // Traitement des formulaires
        Route::post('/login', [AuthenteController::class, 'login'])->name('login.process');
        Route::post('/register', [AuthenteController::class, 'register'])->name('register');
        Route::post('/password/request', [AuthenteController::class, 'request'])->name('request');

        // Réinitialisation de mot de passe
        Route::get('/password/reset/{token}', [AuthenteController::class, 'showResetForm'])->name('password.reset');
        Route::post('/password/reset', [AuthenteController::class, 'resetPassword'])->name('password.update');
    });

    // Routes accessibles aux utilisateurs authentifiés seulement
    Route::middleware('auth')->group(function () {
        // Déconnexion
        Route::post('/logout', [AuthenteController::class, 'logout'])->name('logout');

        // Changement de mot de passe
        Route::post('/password/change', [AuthenteController::class, 'changePassword'])->name('password.change');

        // API pour vérifier le statut des tentatives de changement de mot de passe
        Route::get('/password/status', [AuthenteController::class, 'passwordChangeStatus'])->name('password.status');
        Route::get('/password/stats', [AuthenteController::class, 'getPasswordAttemptStats'])->name('password.stats');
    });
});


