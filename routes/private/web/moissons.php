<?php

use App\Http\Controllers\Private\Web\EngagementMoissonsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Web\MoissonController;
use App\Http\Controllers\Private\Web\PassageMoissonsController;
use App\Http\Controllers\Private\Web\VenteMoissonsController;

// Routes MOISSONS
Route::prefix('moissons')->name('private.moissons.')->middleware(['auth', 'verified', 'user.status'])->group(function () {

    // MOISSONS Management
        Route::get('/', [MoissonController::class, 'index'])->middleware('permission:moissons.read')->name('index');
        Route::get('/create', [MoissonController::class, 'create'])->middleware('permission:moissons.read')->name('create');
        Route::get('/dashboard', [MoissonController::class, 'dashboard'])->middleware('permission:moissons.dashboard')->name('dashboard');

        Route::get('/exporter', [MoissonController::class, 'exporter'])->middleware('permission:moissons.read')->name('exporterall');


        // Route::get('/bord', [MoissonController::class, 'moisson'])->middleware('permission:moissons.read')->name('bord');
        Route::get('moissons/export/liste', [MoissonController::class, 'exporterListeMoissons'])->middleware('permission:moissons.read')->name('moissons.export.liste');
        Route::post('/', [MoissonController::class, 'store'])->middleware('permission:moissons.read')->name('store');

        Route::get('/{moissons}/exporter', [MoissonController::class, 'exporter'])->middleware('permission:moissons.export')->name('exporter');

        Route::get('/{moisson}', [MoissonController::class, 'show'])->middleware('permission:moissons.read')->name('show');
        Route::put('/{moisson}', [MoissonController::class, 'update'])->middleware('permission:moissons.update')->name('update');
        Route::post('/{moisson}/recalculer-totaux', [MoissonController::class, 'recalculertotaux'])->middleware('permission:moissons.recalculer-totaux')->name('recalculer-totaux');
        Route::get('/{moisson}/edit', [MoissonController::class, 'edit'])->middleware('permission:moissons.update')->name('edit');
        Route::post('/{moisson}/cloturer', [MoissonController::class, 'cloturer'])->middleware('permission:moissons.update')->name('cloturer');
        Route::get('/{moisson}/statistiques', [MoissonController::class, 'statistiques'])->middleware('permission:moissons.statistics')->name('statistiques');

        Route::patch('/{moisson}/desactiver', [MoissonController::class, 'desactiver'])->middleware('permission:moissons.toggle-status')->name('desactiver');
        Route::patch('/{moisson}/reactiver', [MoissonController::class, 'reactiver'])->middleware('permission:moissons.toggle-status')->name('reactiver');
        Route::delete('/{moisson}', [MoissonController::class, 'destroy'])->middleware('permission:moissons.delte')->name('destroy');


        Route::get('export/liste', [MoissonController::class, 'exporterListeMoissons'])->middleware('permission:moissons.read')->name('export.liste');
        Route::get('{moisson}/export', [MoissonController::class, 'exporterMoissonComplete'])->middleware('permission:moissons.export')->name('export.complete');

        Route::prefix('{moisson}/passages')->name('passages.')->group(function () {
            Route::get('/', [PassageMoissonsController::class, 'index'])->middleware('permission:passagesmoissons.read')->name('index');
            Route::get('/create', [PassageMoissonsController::class, 'create'])->middleware('permission:passagesmoissons.create')->name('create');
            Route::get('/dashboard', [PassageMoissonsController::class, 'dashboard'])->middleware('permission:passagesmoissons.dashboard')->name('dashboard');

            Route::get('/exporter', [PassageMoissonsController::class, 'exporter'])->middleware('permission:passagesmoissons.export')->name('exporter');
            Route::get('/statistiques', [PassageMoissonsController::class, 'statistiques'])->middleware('permission:passagesmoissons.statistics')->name('statistiques');
            // Route::get('/bord', [PassageMoissonsController::class, 'moisson'])->middleware('permission:passagesmoissons.read')->name('bord');
            Route::post('/', [PassageMoissonsController::class, 'store'])->middleware('permission:passagesmoissons.create')->name('store');

            Route::get('/{passageMoisson}', [PassageMoissonsController::class, 'show'])->middleware('permission:passagesmoissons.read')->name('show');
            Route::post('/ajouter-montant/{passageMoisson}', [PassageMoissonsController::class, 'ajouterMontant'])->middleware('permission:passagesmoissons.update')->name('ajouter-montant');
            Route::post('/toggle-status/{passageMoisson}', [PassageMoissonsController::class, 'toggleStatus'])->middleware('permission:passagesmoissons.toggle-status')->name('toggle-status');
            Route::put('/{passageMoisson}', [PassageMoissonsController::class, 'update'])->middleware('permission:passagesmoissons.update')->name('update');
            Route::get('/edit/{passageMoisson}', [PassageMoissonsController::class, 'edit'])->middleware('permission:passagesmoissons.update')->name('edit');

            Route::patch('/desactiver/{passageMoisson}', [PassageMoissonsController::class, 'desactiver'])->middleware('permission:passagesmoissons.toggle-status')->name('desactiver');
            Route::patch('/reactiver/{passageMoisson}', [PassageMoissonsController::class, 'reactiver'])->middleware('permission:passagesmoissons.toggle-status')->name('reactiver');
            Route::delete('/{passageMoisson}', [PassageMoissonsController::class, 'destroy'])->middleware('permission:passagesmoissons.delete')->name('destroy');
        });

        Route::prefix('{moisson}/ventes')->name('ventes.')->group(function () {
            Route::get('/', [VenteMoissonsController::class, 'index'])->middleware('permission:ventesmoissons.read')->name('index');
            Route::get('/create', [VenteMoissonsController::class, 'create'])->middleware('permission:ventesmoissons.create')->name('create');
            Route::get('/dashboard', [VenteMoissonsController::class, 'dashboard'])->middleware('permission:ventesmoissons.dashboard')->name('dashboard');

            Route::get('/exporter', [VenteMoissonsController::class, 'exporter'])->middleware('permission:ventesmoissons.export')->name('exporter');
            Route::get('/statistiques', [VenteMoissonsController::class, 'statistiques'])->middleware('permission:ventesmoissons.statistics')->name('statistiques');

            Route::post('/', [VenteMoissonsController::class, 'store'])->middleware('permission:ventesmoissons.create')->name('store');

            Route::get('/{venteMoisson}', [VenteMoissonsController::class, 'show'])->middleware('permission:ventesmoissons.read')->name('show');
            Route::post('/{venteMoisson}/ajouter-montant', [VenteMoissonsController::class, 'ajouterMontant'])->middleware('permission:ventesmoissons.update')->name('ajouter-montant');
            Route::post('/{venteMoisson}/toggle-status', [VenteMoissonsController::class, 'toggleStatus'])->middleware('permission:ventesmoissons.toggle-status')->name('toggle-status');
            Route::put('/{venteMoisson}', [VenteMoissonsController::class, 'update'])->middleware('permission:ventesmoissons.read')->name('update');
            Route::get('/{venteMoisson}/edit', [VenteMoissonsController::class, 'edit'])->middleware('permission:ventesmoissons.update')->name('edit');

            Route::patch('/{venteMoisson}/desactiver', [VenteMoissonsController::class, 'desactiver'])->middleware('permission:ventesmoissons.toggle-status')->name('desactiver');
            Route::patch('/{venteMoisson}/reactiver', [VenteMoissonsController::class, 'reactiver'])->middleware('permission:ventesmoissons.toggle-status')->name('reactiver');
            Route::delete('/{venteMoisson}', [VenteMoissonsController::class, 'destroy'])->middleware('permission:ventesmoissons.delete')->name('destroy');
        });

        Route::prefix('{moisson}/engagements')->name('engagements.')->group(function () {
            Route::get('/', [EngagementMoissonsController::class, 'index'])->middleware('permission:engagementsmoissons.read')->name('index');
            Route::get('/create', [EngagementMoissonsController::class, 'create'])->middleware('permission:engagementsmoissons.create')->name('create');
            Route::get('/dashboard', [EngagementMoissonsController::class, 'dashboard'])->middleware('permission:engagementsmoissons.dashboard')->name('dashboard');

            Route::get('/exporter', [EngagementMoissonsController::class, 'exporter'])->middleware('permission:engagementsmoissons.export')->name('exporter');
            Route::get('/statistiques', [EngagementMoissonsController::class, 'statistiques'])->middleware('permission:engagementsmoissons.statistics')->name('statistiques');
            Route::post('/', [EngagementMoissonsController::class, 'store'])->middleware('permission:engagementsmoissons.create')->name('store');

            Route::get('/{engagementMoisson}', [EngagementMoissonsController::class, 'show'])->middleware('permission:engagementsmoissons.read')->name('show');
            Route::post('/{engagementMoisson}/ajouter-montant', [EngagementMoissonsController::class, 'ajouterMontant'])->middleware('permission:engagementsmoissons.update')->name('ajouter-montant');
            Route::post('/{engagementMoisson}/planifier-rappel', [EngagementMoissonsController::class, 'planifierRappel'])->middleware('permission:engagementsmoissons.update')->name('planifier-rappel');
            Route::post('/{engagementMoisson}/toggle-status', [EngagementMoissonsController::class, 'toggleStatus'])->middleware('permission:engagementsmoissons.toggle-status')->name('toggle-status');
            Route::post('/{engagementMoisson}/prolonger-echeance', [EngagementMoissonsController::class, 'prolongerEcheance'])->middleware('permission:engagementsmoissons.update')->name('prolonger-echeance');
            Route::put('/{engagementMoisson}', [EngagementMoissonsController::class, 'update'])->middleware('permission:engagementsmoissons.update')->name('update');
            Route::get('/{engagementMoisson}/edit', [EngagementMoissonsController::class, 'edit'])->middleware('permission:engagementsmoissons.update')->name('edit');

            Route::patch('/{engagementMoisson}/desactiver', [EngagementMoissonsController::class, 'desactiver'])->middleware('permission:engagementsmoissons.toggle-status')->name('desactiver');
            Route::patch('/{engagementMoisson}/reactiver', [EngagementMoissonsController::class, 'reactiver'])->middleware('permission:engagementsmoissons.toggle-status')->name('reactiver');
            Route::delete('/{engagementMoisson}', [EngagementMoissonsController::class, 'destroy'])->middleware('permission:engagementsmoissons.delete')->name('destroy');
        });


});










