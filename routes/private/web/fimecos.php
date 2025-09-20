<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Web\FimecoController;
use App\Http\Controllers\Private\Web\PaiementController;
use App\Http\Controllers\Private\Web\SubscriptionController;






// Routes FIMECO
Route::prefix('dashboard')->name('private.')->middleware(['auth', 'verified', 'user.status'])->group(function () {

    // FIMECO Management
    Route::prefix('fimecos')->name('fimecos.')->group(function () {
        Route::get('/', [FimecoController::class, 'index'])->name('index');
        Route::get('/create', [FimecoController::class, 'create'])->name('create');

        Route::get('/dashboard', [FimecoController::class, 'dashboard'])->name('dashboard');
        Route::get('/rapport', [FimecoController::class, 'rapport'])->name('rapport');

        Route::get('/export', [FimecoController::class, 'export'])->name('export');
        Route::get('/search', [FimecoController::class, 'search'])->name('search');
        Route::get('/liveStats', [FimecoController::class, 'liveStats'])->name('liveStats');
        Route::post('/', [FimecoController::class, 'store'])->name('store');
        Route::post('/validateFimecoData', [FimecoController::class, 'validateFimecoData'])->name('validateFimecoData');
        Route::get('/{fimeco}', [FimecoController::class, 'show'])->name('show');
        Route::delete('/{fimeco}', [FimecoController::class, 'destroy'])->name('destroy');
        Route::put('/{fimeco}', [FimecoController::class, 'update'])->name('update');
         Route::get('/{fimeco}/edit', [FimecoController::class, 'edit'])->name('edit');
        Route::post('/{fimeco}/cloture', [FimecoController::class, 'cloture'])->name('cloture');
        Route::post('/{fimeco}/reouvrir', [FimecoController::class, 'reouvrir'])->name('reouvrir');

        Route::get('/{fimeco}/statistiques', [FimecoController::class, 'statistiques'])->name('statistiques');

        Route::patch('/{fimeco}/desactiver', [FimecoController::class, 'desactiver'])->name('desactiver');
        Route::patch('/{fimeco}/reactiver', [FimecoController::class, 'reactiver'])->name('reactiver');
    });

    // Subscriptions
    Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
        // Routes sans paramètres
        Route::get('/', [SubscriptionController::class, 'index'])->name('index');
        Route::post('/', [SubscriptionController::class, 'store'])->name('store');
        Route::get('/mes-statistiques', [SubscriptionController::class, 'mesStatistiques'])->name('mesStatistiques');
        Route::get('/dashboard', [SubscriptionController::class, 'dashboard'])->name('dashboard');

        Route::get('/export', [SubscriptionController::class, 'export'])->name('export');

        Route::post('/validate-data', [SubscriptionController::class, 'validateSubscriptionData'])->name('validate-data');
        Route::post('/check-exists', [SubscriptionController::class, 'checkExists'])->name('check-exists');
         Route::get('/create/{fimeco}', [SubscriptionController::class, 'create'])->name('create');
        Route::get('/{subscription}', [SubscriptionController::class, 'show'])->name('show');
        Route::post('/{subscription}/effectuer-paiement', [SubscriptionController::class, 'effectuerPaiement'])->name('effectuer-paiement');
        Route::post('/{subscription}/simuler-paiement', [SubscriptionController::class, 'simulerPaiement'])->name('simuler-paiement');
        Route::post('/{subscription}/desactiver', [SubscriptionController::class, 'desactiver'])->name('desactiver');
        Route::post('/{subscription}/validate', [SubscriptionController::class, 'validate'])->name('validate');
        Route::post('/{subscription}/reactiver', [SubscriptionController::class, 'reactiver'])->name('reactiver');
        Route::post('/{subscription}/rapport', [SubscriptionController::class, 'rapport'])->name('rapport');
        // Routes avec paramètres spécifiques
        Route::get('peut-souscrire/{fimeco}', [SubscriptionController::class, 'peutSouscrire'])->name('peut_souscrire');

        Route::delete('/{subscription}', [SubscriptionController::class, 'destroy'])->name('destroy');
        Route::patch('/{fimeco}/user-disponibles', [SubscriptionController::class, 'usersDisponibles'])->name('user-disponibles');

        // Routes CRUD génériques (en dernier)

        Route::get('/{subscription}/edit', [SubscriptionController::class, 'edit'])->name('edit');
        Route::put('/{subscription}', [SubscriptionController::class, 'update'])->name('update');
        Route::patch('/{subscription}/annuler', [SubscriptionController::class, 'annuler'])->name('annuler');
        Route::patch('/{subscription}/suspendre', [SubscriptionController::class, 'suspendre'])->name('suspendre');
    });


    // Payments
    Route::prefix('paiements')->name('paiements.')->group(function () {
    // Routes fixes sans paramètres
    Route::get('/', [PaiementController::class, 'index'])->name('index');
    Route::post('/', [PaiementController::class, 'store'])->name('store');

    // Routes fixes spécifiques
    Route::get('/en-attente', [PaiementController::class, 'enAttente'])->name('en-attente');
    Route::post('/traiter-en-lot', [PaiementController::class, 'traiterEnLot'])->name('traiter-en-lot');
    Route::get('/types-paiement', [PaiementController::class, 'typesPaiement'])->name('types_paiement');

    Route::post('/{payment}/validate', [PaiementController::class, 'valider'])->name('validate');
    Route::post('/{payment}/reject', [PaiementController::class, 'reject'])->name('reject');

     Route::get('/{payment}', [PaiementController::class, 'show'])->name('show');
    // Routes avec paramètres + action spécifique
    Route::get('/{subscription}/create', [PaiementController::class, 'create'])->name('create');
    Route::get('/{payment}/edit', [PaiementController::class, 'edit'])->name('edit');


    // Actions sur un paiement spécifique
    Route::post('/{payment}/valider', [PaiementController::class, 'valider'])->name('valider');


    // Route générale EN DERNIER

});


});



