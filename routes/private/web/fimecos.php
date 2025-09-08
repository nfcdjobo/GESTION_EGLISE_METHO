<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Web\FimecoController;
use App\Http\Controllers\Private\Web\PaymentController;
use App\Http\Controllers\Private\Web\SubscriptionController;






// Routes FIMECO
Route::prefix('dashboard')->name('private.')->middleware(['auth', 'verified', 'user.status'])->group(function () {

    // FIMECO Management
    Route::prefix('fimecos')->name('fimecos.')->group(function () {
        Route::get('/', [FimecoController::class, 'index'])->name('index');
        Route::get('/create', [FimecoController::class, 'create'])->name('create');

        Route::get('/bord', [FimecoController::class, 'fimeco'])->name('bord');
        Route::post('/', [FimecoController::class, 'store'])->name('store');
        Route::get('/{fimeco}', [FimecoController::class, 'show'])->name('show');
        Route::put('/{fimeco}', [FimecoController::class, 'update'])->name('update');
         Route::get('/{fimeco}/edit', [FimecoController::class, 'edit'])->name('edit');
        Route::post('/{fimeco}/cloturer', [FimecoController::class, 'cloturer'])->name('cloturer');
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
        Route::get('fimecos-disponibles', [SubscriptionController::class, 'fimecosDisponibles'])->name('fimecos_disponibles');
        Route::get('/create', [SubscriptionController::class, 'create'])->name('create');
        Route::get('/{subscription}', [SubscriptionController::class, 'show'])->name('show');
        // Routes avec paramètres spécifiques  
        Route::get('peut-souscrire/{fimeco}', [SubscriptionController::class, 'peutSouscrire'])->name('peut_souscrire');
        
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
    Route::get('/', [PaymentController::class, 'index'])->name('index');
    Route::post('/', [PaymentController::class, 'store'])->name('store');
    
    // Routes fixes spécifiques
    Route::get('/en-attente', [PaymentController::class, 'enAttente'])->name('en-attente');
    Route::post('/traiter-en-lot', [PaymentController::class, 'traiterEnLot'])->name('traiter-en-lot');
    Route::get('/types-paiement', [PaymentController::class, 'typesPaiement'])->name('types_paiement');
    
    // Routes avec paramètres + action spécifique
    Route::get('/{subscription}/create', [PaymentController::class, 'create'])->name('create');
    Route::get('/{payment}/edit', [PaymentController::class, 'edit'])->name('edit');
    
    // Actions sur un paiement spécifique
    Route::post('/{payment}/valider', [PaymentController::class, 'valider'])->name('valider');
    Route::post('/{payment}/refuser', [PaymentController::class, 'refuser'])->name('refuser');
    Route::post('/{payment}/annuler', [PaymentController::class, 'annuler'])->name('annuler');
    
    // Route générale EN DERNIER
    Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
});


});



