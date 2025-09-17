<?php

use App\Http\Controllers\Private\Web\EngagementMoissonsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Web\MoissonController;
use App\Http\Controllers\Private\Web\PassageMoissonsController;
use App\Http\Controllers\Private\Web\VenteMoissonsController;

// Routes MOISSONS
Route::prefix('dashboard')->name('private.')->middleware(['auth', 'verified', 'user.status'])->group(function () {

    // MOISSONS Management
    Route::prefix('moissons')->name('moissons.')->group(function () {
        Route::get('/', [MoissonController::class, 'index'])->name('index');
        Route::get('/create', [MoissonController::class, 'create'])->name('create');
        Route::get('/dashboard', [MoissonController::class, 'dashboard'])->name('dashboard');

        Route::get('/exporter', [MoissonController::class, 'exporter'])->name('exporterall');





        Route::get('/bord', [MoissonController::class, 'moisson'])->name('bord');
        Route::get('moissons/export/liste', [MoissonController::class, 'exporterListeMoissons'])->name('moissons.export.liste');
        Route::post('/', [MoissonController::class, 'store'])->name('store');

        Route::get('/{moissons}/exporter', [MoissonController::class, 'exporter'])->name('exporter');

        Route::get('/{moisson}', [MoissonController::class, 'show'])->name('show');
        Route::put('/{moisson}', [MoissonController::class, 'update'])->name('update');
        Route::post('/{moisson}/recalculer-totaux', [MoissonController::class, 'recalculertotaux'])->name('recalculer-totaux');
        Route::get('/{moisson}/edit', [MoissonController::class, 'edit'])->name('edit');
        Route::post('/{moisson}/cloturer', [MoissonController::class, 'cloturer'])->name('cloturer');
        Route::get('/{moisson}/statistiques', [MoissonController::class, 'statistiques'])->name('statistiques');

        Route::patch('/{moisson}/desactiver', [MoissonController::class, 'desactiver'])->name('desactiver');
        Route::patch('/{moisson}/reactiver', [MoissonController::class, 'reactiver'])->name('reactiver');
        Route::delete('/{moisson}', [MoissonController::class, 'destroy'])->name('destroy');


    Route::get('export/liste', [MoissonController::class, 'exporterListeMoissons'])->name('export.liste');
    Route::get('{moisson}/export', [MoissonController::class, 'exporterMoissonComplete'])->name('export.complete');



        Route::prefix('{moisson}/passages')->name('passages.')->group(function () {
            Route::get('/', [PassageMoissonsController::class, 'index'])->name('index');
            Route::get('/create', [PassageMoissonsController::class, 'create'])->name('create');
            Route::get('/dashboard', [PassageMoissonsController::class, 'dashboard'])->name('dashboard');

            Route::get('/exporter', [PassageMoissonsController::class, 'exporter'])->name('exporter');
             Route::get('/statistiques', [PassageMoissonsController::class, 'statistiques'])->name('statistiques');
            Route::get('/bord', [PassageMoissonsController::class, 'moisson'])->name('bord');
            Route::post('/', [PassageMoissonsController::class, 'store'])->name('store');

            Route::get('/{passageMoisson}', [PassageMoissonsController::class, 'show'])->name('show');
            Route::post('/ajouter-montant/{passageMoisson}', [PassageMoissonsController::class, 'ajouterMontant'])->name('ajouter-montant');
            Route::post('/toggle-status/{passageMoisson}', [PassageMoissonsController::class, 'toggleStatus'])->name('toggle-status');
            Route::put('/{passageMoisson}', [PassageMoissonsController::class, 'update'])->name('update');
            Route::get('/edit/{passageMoisson}', [PassageMoissonsController::class, 'edit'])->name('edit');

            Route::patch('/desactiver/{passageMoisson}', [PassageMoissonsController::class, 'desactiver'])->name('desactiver');
            Route::patch('/reactiver/{passageMoisson}', [PassageMoissonsController::class, 'reactiver'])->name('reactiver');
            Route::delete('/{passageMoisson}', [PassageMoissonsController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('{moisson}/ventes')->name('ventes.')->group(function () {
            Route::get('/', [VenteMoissonsController::class, 'index'])->name('index');
            Route::get('/create', [VenteMoissonsController::class, 'create'])->name('create');
            Route::get('/dashboard', [VenteMoissonsController::class, 'dashboard'])->name('dashboard');

            Route::get('/exporter', [VenteMoissonsController::class, 'exporter'])->name('exporter');
             Route::get('/statistiques', [VenteMoissonsController::class, 'statistiques'])->name('statistiques');
            Route::get('/bord', [VenteMoissonsController::class, 'moisson'])->name('bord');
            Route::post('/', [VenteMoissonsController::class, 'store'])->name('store');

            Route::get('/{venteMoisson}', [VenteMoissonsController::class, 'show'])->name('show');
            Route::post('/{venteMoisson}/ajouter-montant', [VenteMoissonsController::class, 'ajouterMontant'])->name('ajouter-montant');
            Route::post('/{venteMoisson}/toggle-status', [VenteMoissonsController::class, 'toggleStatus'])->name('toggle-status');
            Route::put('/{venteMoisson}', [VenteMoissonsController::class, 'update'])->name('update');
            Route::get('/{venteMoisson}/edit', [VenteMoissonsController::class, 'edit'])->name('edit');

            Route::patch('/{venteMoisson}/desactiver', [VenteMoissonsController::class, 'desactiver'])->name('desactiver');
            Route::patch('/{venteMoisson}/reactiver', [VenteMoissonsController::class, 'reactiver'])->name('reactiver');
            Route::delete('/{venteMoisson}', [VenteMoissonsController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('{moisson}/engagements')->name('engagements.')->group(function () {
            Route::get('/', [EngagementMoissonsController::class, 'index'])->name('index');
            Route::get('/create', [EngagementMoissonsController::class, 'create'])->name('create');
            Route::get('/dashboard', [EngagementMoissonsController::class, 'dashboard'])->name('dashboard');

            Route::get('/exporter', [EngagementMoissonsController::class, 'exporter'])->name('exporter');
             Route::get('/statistiques', [EngagementMoissonsController::class, 'statistiques'])->name('statistiques');
            Route::get('/bord', [EngagementMoissonsController::class, 'moisson'])->name('bord');
            Route::post('/', [EngagementMoissonsController::class, 'store'])->name('store');

            Route::get('/{engagementMoisson}', [EngagementMoissonsController::class, 'show'])->name('show');
            Route::post('/{engagementMoisson}/ajouter-montant', [EngagementMoissonsController::class, 'ajouterMontant'])->name('ajouter-montant');
            Route::post('/{engagementMoisson}/planifier-rappel', [EngagementMoissonsController::class, 'planifierRappel'])->name('planifier-rappel');
            Route::post('/{engagementMoisson}/toggle-status', [EngagementMoissonsController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/{engagementMoisson}/prolonger-echeance', [EngagementMoissonsController::class, 'prolongerEcheance'])->name('prolonger-echeance');
            Route::put('/{engagementMoisson}', [EngagementMoissonsController::class, 'update'])->name('update');
            Route::get('/{engagementMoisson}/edit', [EngagementMoissonsController::class, 'edit'])->name('edit');

            Route::patch('/{engagementMoisson}/desactiver', [EngagementMoissonsController::class, 'desactiver'])->name('desactiver');
            Route::patch('/{engagementMoisson}/reactiver', [EngagementMoissonsController::class, 'reactiver'])->name('reactiver');
            Route::delete('/{engagementMoisson}', [EngagementMoissonsController::class, 'destroy'])->name('destroy');
        });
    });

});










