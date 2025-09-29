<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Web\CulteController;
use App\Http\Controllers\Private\Web\ParticipantCulteController;

/*
|--------------------------------------------------------------------------
| Routes des Cultes
|--------------------------------------------------------------------------
|
| Toutes les routes pour la gestion des cultes religieux
| Organisées dans un groupe avec middleware d'authentification
|
*/

Route::prefix('cultes')->name('private.cultes.')->middleware(['auth', 'verified', 'user.status'])->group(function () {

    Route::get('/', [CulteController::class, 'index'])->middleware('permission:cultes.read')->name('index');

    Route::get('/create', [CulteController::class, 'create'])->middleware('permission:cultes.create')->name('create');



    Route::post('/', [CulteController::class, 'store'])->middleware('permission:cultes.create')->name('store');

    // Route avec cache pour les statistiques
    Route::get('/statistiques/cached', [CulteController::class, 'statistiques'])->middleware('permission:cultes.statistics')->name('statistiques.cached');

    // Pages spécialisées
    Route::get('/planning', [CulteController::class, 'planning'])->middleware('permission:cultes.planning')->name('planning');

    Route::get('/statistiques', [CulteController::class, 'statistiques'])->middleware('permission:cultes.statistics')->name('statistiques');

    Route::get('/dashboard', [CulteController::class, 'dashboard'])->middleware('permission:cultes.dashboard')->name('dashboard');

    // Export multiple de cultes
    Route::post('export/multiple', [CulteController::class, 'exportMultiple'])->name('export.multiple')->middleware('permission:cultes.read');

    // Route pour télécharger les exports multiples directement
    Route::get('export/multiple/pdf', [CulteController::class, 'exportMultiplePdfDirect'])->name('export.multiple.pdf')->middleware('permission:cultes.read');

    Route::get('export/multiple/excel', [CulteController::class, 'exportMultipleExcelDirect'])->name('export.multiple.excel')->middleware('permission:cultes.read');


    Route::get('/{culte}', [CulteController::class, 'show'])->middleware('permission:cultes.read')->name('show');

    Route::get('/{culte}/edit', [CulteController::class, 'edit'])->middleware('permission:cultes.update')->name('edit');


    Route::put('/{culte}', [CulteController::class, 'update'])->middleware('permission:cultes.update')->name('update');

    Route::delete('/{culte}', [CulteController::class, 'destroy'])->middleware('permission:cultes.delte')->name('destroy');

    Route::get('/{culte}/get-officiants', [CulteController::class, 'getOfficiants'])->middleware('permission:cultes.read')->name('officiants.get');
    Route::post('/{culte}/ajouter-officiant', [CulteController::class, 'ajouterOfficiant'])->middleware('permission:cultes.update')->name('officiants.ajouter');
    Route::delete('/{culte}/supprimer-officiant', [CulteController::class, 'supprimerOfficiant'])->middleware('permission:cultes.delete')->name('officiants.supprimer');

    // Route avec validation UUID stricte
    Route::get('/{culte}/strict', [CulteController::class, 'show'])->where('culte', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}')->middleware('permission:cultes.read')->name('show.strict');

    // Gestion du statut des cultes
    Route::post('/{culte}/statut', [CulteController::class, 'changerStatut'])->middleware('permission:cultes.change-status')->name('statut');


    // Duplication de culte
    Route::post('/{culte}/dupliquer', [CulteController::class, 'dupliquer'])->middleware('permission:cultes.duplicate')->name('dupliquer');

    // Restauration d'un culte supprimé
    Route::patch('/{id}/restore', [CulteController::class, 'restore'])->middleware('permission:cultes.restore')->name('restore')->withTrashed();


    // Routes spécifiques pour les participants d'un culte
    Route::get('/{culte}/participants', [ParticipantCulteController::class, 'participantsCulte'])->middleware('permission:cultes.manage-participants')->name('participants')->where('culte', '[0-9a-f-]{36}');

    // Export individuel d'un culte
    Route::get('{culte}/export/pdf', [CulteController::class, 'exportPdf'])->name('export.pdf')->middleware('permission:cultes.read');

    Route::get('{culte}/export/excel', [CulteController::class, 'exportExcel'])->name('export.excel')->middleware('permission:cultes.read');

    Route::post('/{culte}/participants/ajouter', [ParticipantCulteController::class, 'ajouterParticipant'])->middleware('permission:cultes.manage-participants')->name('participants.ajouter')->where('culte', '[0-9a-f-]{36}');

});



