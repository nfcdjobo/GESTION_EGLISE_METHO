<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Private\Web\ParticipantCulteController;

/*
|--------------------------------------------------------------------------
| Routes ParticipantCulte
|--------------------------------------------------------------------------
|
| Routes pour la gestion des participations aux cultes
|
*/

Route::prefix('private/participants-cultes')->name('private.participantscultes.')->middleware(['auth', 'verified', 'user.status'])->group(function () {

    // Routes CRUD de base
    Route::get('/', [ParticipantCulteController::class, 'index'])->name('index');

    Route::get('/nouveaux-visiteurs', [ParticipantCulteController::class, 'nouveauxVisiteurs'])->name('nouveaux-visiteurs');

     Route::get('/{culte}/search', [ParticipantCulteController::class, 'searchParticipants'])->name('search')->where('culte', '[0-9a-f-]{36}');

    Route::post('/{culte}/participants/ajouter', [ParticipantCulteController::class, 'ajouterParticipant'])
        ->name('private.cultes.participants.ajouter')
        ->where('culte', '[0-9a-f-]{36}');

    Route::post('/', [ParticipantCulteController::class, 'store'])->name('store');
    Route::get('/{participantId}/{culteId}', [ParticipantCulteController::class, 'show'])->name('show')->where(['participantId' => '[0-9a-f-]{36}', 'culteId' => '[0-9a-f-]{36}']);

    Route::put('/{participantId}/{culteId}', [ParticipantCulteController::class, 'update'])->name('update')->where(['participantId' => '[0-9a-f-]{36}', 'culteId' => '[0-9a-f-]{36}']);

    Route::delete('/{participantId}/{culteId}', [ParticipantCulteController::class, 'destroy'])->name('destroy')->where(['participantId' => '[0-9a-f-]{36}', 'culteId' => '[0-9a-f-]{36}']);

    // Routes spécialisées pour création avec utilisateurs
    Route::post('/with-user-creation', [ParticipantCulteController::class, 'storeWithUserCreation'])->name('store-with-user-creation')->where('culteId', '[0-9a-f-]{36}');

    Route::post('/bulk-with-user-creation', [ParticipantCulteController::class, 'storeBulkWithUserCreation'])->where('culteId', '[0-9a-f-]{36}');

    // Route pour confirmation de présence
    Route::patch('/{participantId}/{culteId}/confirmer', [ParticipantCulteController::class, 'confirmerPresence'])->name('confirmer-presence')->where(['participantId' => '[0-9a-f-]{36}', 'culteId' => '[0-9a-f-]{36}']);

    // Routes pour statistiques et rapports
    Route::get('/statistiques', [ParticipantCulteController::class, 'statistiques'])->name('statistiques');



});


