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



Route::prefix('participants-cultes')->name('private.participantscultes.')->middleware(['auth', 'verified', 'user.status'])->group(function () {

    // Routes CRUD de base
    Route::get('/', [ParticipantCulteController::class, 'index'])->middleware('permission:participant_cultes.read')->name('index');

    Route::get('/nouveaux-visiteurs', [ParticipantCulteController::class, 'nouveauxVisiteurs'])->middleware('permission:participant_cultes.read')->name('nouveaux-visiteurs');

    // Routes pour statistiques et rapports
    Route::get('/statistiques', [ParticipantCulteController::class, 'statistiques'])->middleware('permission:participant_cultes.statistics')->name('statistiques');

    Route::get('/{culte}/search', [ParticipantCulteController::class, 'searchParticipants'])->middleware('permission:participant_cultes.read')->name('search')->where('culte', '[0-9a-f-]{36}');
    Route::get('/{participantId}/detail/{culteId}', [ParticipantCulteController::class, 'show'])->middleware('permission:participant_cultes.read')->name('show')->where(['participantId' => '[0-9a-f-]{36}', 'culteId' => '[0-9a-f-]{36}']);

    Route::post('/', [ParticipantCulteController::class, 'store'])->middleware('permission:participant_cultes.create')->name('store');
    Route::post('/{culte}/participants/ajouter', [ParticipantCulteController::class, 'ajouterParticipant'])->middleware('permission:participant_cultes.update')->name('private.cultes.participants.ajouter')->where('culte', '[0-9a-f-]{36}');

    Route::put('/{participantId}/{culteId}', [ParticipantCulteController::class, 'update'])->middleware('permission:participant_cultes.update')->name('update')->where(['participantId' => '[0-9a-f-]{36}', 'culteId' => '[0-9a-f-]{36}']);

    Route::delete('/{participantId}/{culteId}', [ParticipantCulteController::class, 'destroy'])->middleware('permission:participant_cultes.delete')->name('destroy')->where(['participantId' => '[0-9a-f-]{36}', 'culteId' => '[0-9a-f-]{36}']);

    // Routes spécialisées pour création avec membres
    Route::post('/with-user-creation', [ParticipantCulteController::class, 'storeWithUserCreation'])->middleware('permission:participant_cultes.create')->name('store-with-user-creation')->where('culteId', '[0-9a-f-]{36}');

    Route::post('/bulk-with-user-creation', [ParticipantCulteController::class, 'storeBulkWithUserCreation'])->middleware('permission:participant_cultes.create')->where('culteId', '[0-9a-f-]{36}');

    // Route pour confirmation de présence
    Route::patch('/{participantId}/{culteId}/confirmer', [ParticipantCulteController::class, 'confirmerPresence'])->middleware('permission:participant_cultes.update')->name('confirmer-presence')->where(['participantId' => '[0-9a-f-]{36}', 'culteId' => '[0-9a-f-]{36}']);

});


