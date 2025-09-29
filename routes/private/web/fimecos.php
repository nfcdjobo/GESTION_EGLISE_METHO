<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Web\FimecoController;
use App\Http\Controllers\Private\Web\PaiementController;
use App\Http\Controllers\Private\Web\SubscriptionController;






// Routes FIMECO
Route::prefix('dashboard')->name('private.')->middleware(['auth', 'verified', 'user.status'])->group(function () {

    // FIMECO Management
    Route::prefix('fimecos')->name('fimecos.')->group(function () {
        Route::get('/', [FimecoController::class, 'index'])->middleware('permission:fimecos.read')->name('index');
        Route::get('/create', [FimecoController::class, 'create'])->middleware('permission:fimecos.create')->name('create');

        Route::get('/dashboard', [FimecoController::class, 'dashboard'])->middleware('permission:fimecos.dashboard')->name('dashboard');

        Route::get('/export', [FimecoController::class, 'export'])->middleware('permission:fimecos.export')->name('export');
        Route::get('/search', [FimecoController::class, 'search'])->middleware('permission:fimecos.search')->name('search');
        Route::get('/liveStats', [FimecoController::class, 'liveStats'])->middleware('permission:fimecos.live-stats')->name('liveStats');
        Route::post('/', [FimecoController::class, 'store'])->middleware('permission:fimecos.create')->name('store');
        Route::post('/validateFimecoData', [FimecoController::class, 'validateFimecoData'])->middleware('permission:fimecos.validate-data')->name('validateFimecoData');
        Route::get('/{fimeco}', [FimecoController::class, 'show'])->middleware('permission:fimecos.read')->name('show');
        Route::delete('/{fimeco}', [FimecoController::class, 'destroy'])->middleware('permission:fimecos.delete')->name('destroy');
        Route::put('/{fimeco}', [FimecoController::class, 'update'])->middleware('permission:fimecos.update')->name('update');
        Route::get('/{fimeco}/edit', [FimecoController::class, 'edit'])->middleware('permission:fimecos.update')->name('edit');
        Route::post('/{fimeco}/cloture', [FimecoController::class, 'cloture'])->middleware('permission:fimecos.cloture')->name('cloture');
        Route::post('/{fimeco}/reouvrir', [FimecoController::class, 'reouvrir'])->middleware('permission:fimecos.reouvrir')->name('reouvrir');

        Route::get('/{fimeco}/rapport', [FimecoController::class, 'rapport'])->middleware('permission:fimecos.rapport')->name('rapport');

        Route::get('/{fimeco}/statistiques', [FimecoController::class, 'statistiques'])->middleware('permission:fimecos.statistiques')->name('statistiques');

        Route::patch('/{fimeco}/desactiver', [FimecoController::class, 'desactiver'])->middleware('permission:fimecos.desactiver')->name('desactiver');
        Route::patch('/{fimeco}/reactiver', [FimecoController::class, 'reactiver'])->middleware('permission:fimecos.reactiver')->name('reactiver');
    });

    // Subscriptions
    Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
        // Routes sans paramètres
        Route::get('/', [SubscriptionController::class, 'index'])->middleware('permission:subscriptions.read')->name('index');
        Route::post('/', [SubscriptionController::class, 'store'])->middleware('permission:subscriptions.create')->name('store');
        Route::get('/statistiques', [SubscriptionController::class, 'mesStatistiques'])->middleware('permission:subscriptions.mes-statistiques')->name('mesStatistiques');
        Route::get('/dashboard', [SubscriptionController::class, 'dashboard'])->middleware('permission:subscriptions.dashboard')->name('dashboard');

        Route::get('/export', [SubscriptionController::class, 'export'])->middleware('permission:subscriptions.export')->name('export');
        Route::get('/live-stats', [SubscriptionController::class, 'liveStats'])->middleware('permission:subscriptions.read')->name('live-stats');

        Route::post('/validate-data', [SubscriptionController::class, 'validateSubscriptionData'])->middleware('permission:subscriptions.validate-data')->name('validate-data');
        Route::post('/check-exists', [SubscriptionController::class, 'checkExists'])->middleware('permission:subscriptions.update')->name('check-exists');
        Route::get('/create/{fimeco}', [SubscriptionController::class, 'create'])->middleware('permission:subscriptions.create')->name('create');
        Route::get('/{subscription}', [SubscriptionController::class, 'show'])->middleware('permission:subscriptions.read')->name('show');
        Route::post('/{subscription}/effectuer-paiement', [SubscriptionController::class, 'effectuerPaiement'])->middleware('permission:subscriptions.paiement')->name('effectuer-paiement');
        // Route::post('/{subscription}/simuler-paiement', [SubscriptionController::class, 'simulerPaiement'])->middleware('permission:subscriptions.read')->name('simuler-paiement');
        Route::post('/{subscription}/desactiver', [SubscriptionController::class, 'desactiver'])->middleware('permission:subscriptions.desactiver')->name('desactiver');
        Route::post('/{subscription}/validate', [SubscriptionController::class, 'validate'])->middleware('permission:subscriptions.validate')->name('validate');
        Route::post('/{subscription}/reactiver', [SubscriptionController::class, 'reactiver'])->middleware('permission:subscriptions.reactiver')->name('reactiver');
        Route::post('/{subscription}/rapport', [SubscriptionController::class, 'rapport'])->middleware('permission:subscriptions.rapport')->name('rapport');
        // Routes avec paramètres spécifiques
        Route::get('peut-souscrire/{fimeco}', [SubscriptionController::class, 'peutSouscrire'])->middleware('permission:subscriptions.read')->name('peut_souscrire');

        Route::delete('/{subscription}', [SubscriptionController::class, 'destroy'])->middleware('permission:subscriptions.delete')->name('destroy');
        Route::patch('/{fimeco}/user-disponibles', [SubscriptionController::class, 'usersDisponibles'])->middleware('permission:subscriptions.update')->name('user-disponibles');

        // Routes CRUD génériques (en dernier)

        Route::get('/{subscription}/edit', [SubscriptionController::class, 'edit'])->middleware('permission:subscriptions.update')->name('edit');
        Route::put('/{subscription}', [SubscriptionController::class, 'update'])->middleware('permission:subscriptions.update')->name('update');
        Route::patch('/{subscription}/annuler', [SubscriptionController::class, 'annuler'])->middleware('permission:subscriptions.annuler')->name('annuler');
        Route::patch('/{subscription}/suspendre', [SubscriptionController::class, 'suspendre'])->middleware('permission:subscriptions.suspendre')->name('suspendre');
    });


    // Payments
    Route::prefix('paiements')->name('paiements.')->group(function () {
        // Routes fixes sans paramètres
        Route::get('/', [PaiementController::class, 'index'])->middleware('permission:paiements.read')->name('index');
        Route::post('/', [PaiementController::class, 'store'])->middleware('permission:paiements.create')->name('store');

        // Routes fixes spécifiques
        Route::get('/en-attente', [PaiementController::class, 'enAttente'])->middleware('permission:paiements.en-attente')->name('en-attente');
        Route::post('/traiter-en-lot', [PaiementController::class, 'traiterEnLot'])->middleware('permission:paiements.traiter-en-lot')->name('traiter-en-lot');
        Route::get('/types-paiement', [PaiementController::class, 'typesPaiement'])->middleware('permission:paiements.types-paiement')->name('types_paiement');

        Route::post('/{payment}/validate', [PaiementController::class, 'valider'])->middleware('permission:paiements.validate')->name('validate');
        Route::post('/{payment}/reject', [PaiementController::class, 'reject'])->middleware('permission:paiements.reject')->name('reject');

        Route::get('/{payment}', [PaiementController::class, 'show'])->middleware('permission:paiements.read')->name('show');
        // Routes avec paramètres + action spécifique
        Route::get('/{subscription}/create', [PaiementController::class, 'create'])->middleware('permission:create')->name('create');
        Route::get('/{payment}/edit', [PaiementController::class, 'edit'])->middleware('permission:paiements.update')->name('edit');

        // Actions sur un paiement spécifique
        Route::post('/{payment}/valider', [PaiementController::class, 'valider'])->middleware('permission:paiements.validate')->name('valider');
    });


});



